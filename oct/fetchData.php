<?php

function main() {
  // Needed to get country for a project.
  getProjects();

  // For each project and year.
  $years = range(2018, date('Y'));
  foreach ($years as $year) {
    GetCostPlanBreakDown($year);

    $filename = 'projects_with_year_' . $year . '.json';
    $result = json_decode(file_get_contents($filename), TRUE);
    foreach ($result as $project) {
      $iso3 = getProjectCountry($project['id']);
      if ($iso3) {
        GetContributionSummaryForOCHAOnline($year, $project['id'], $iso3);
      }
    }
  }
}

function getProjectCountry($project_id) {
  $filename = 'projects_with_country.json';
  $result = json_decode(file_get_contents($filename), TRUE);
  foreach ($result as $project) {
    if ($project['id'] == $project_id) {
      return strtolower($project['country_code']);
    }
  }

  return FALSE;
}

function getProjects() {
  $filename = 'projects_with_country.json';
  $result = file_get_contents('https://oct.unocha.org/api/ochaonline/getProjects.xml');

  $xml = simplexml_load_string($result);
  $result = $xml->xpath('//Project');

  $data = [];
  foreach ($result as $item) {
    if (count($item->children()) == 0) {
      $data[] = [
        'id' => (string) $item['ProjectID'],
        'name' => (string) $item['ProjectName'],
        'group_id' => (string) $item['ProjectGroupID'],
        'country_id' => (string) $item['CountryID'],
        'country_name' => (string) $item['CountryName'],
        'country_code' => (string) $item['CountryCode'],
      ];
    }
  }

  file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
}

function GetContributionSummaryForOCHAOnline($year = 2022, $project_id = 2310, $iso3) {
  print "Processing project $project_id for $year\n";

  $name = 'GetContributionSummaryForOCHAOnline';

  $query = [
    'WebMethod' => $name,
    'groupID' => 192,
    'projectId' => $project_id,
    'year' => $year,
  ];

  $filename = $name . '_' . $year . '_' . $project_id . '.json';
  $result = getDataFromApi('WebServiceCaller.aspx', $query);

  $oct_response = simplexml_load_string($result);
  $oct_response = json_encode($oct_response);
  $oct_response = json_decode($oct_response, TRUE);

  // Massage the data.
  $openingBalance = 0;
  $totalRequirements = 0;
  $totalUnearmarked = 0;
  $totalDonations = 0;
  $earmarked_donors = array();

  $listContribution = FALSE;
  if (isset($oct_response['listContribution']['WSOCHAOnlineContribution'])) {
    $listContribution = $oct_response['listContribution']['WSOCHAOnlineContribution'];
  }
  $openingBalance += $oct_response['OpeningBalance'];
  $totalRequirements += $oct_response['TotalRequirements'];
  $unearmarked = $oct_response['UnearmarkedContributionDonors'];

  if (is_array($listContribution)) {
    if (!isset($listContribution[0])) {
      $listContribution = array($listContribution);
    }

    for ($row = 0; $row < count($listContribution); $row++) {
      $donorID = $listContribution[$row]['@attributes']['DonorId'];

      $donor_name = $listContribution[$row]['@attributes']['FundingSourceName'];
      $donor_name = trim($donor_name);
      if ($donor_name == '') {
        continue;
      }

      $totalUnearmarked += $listContribution[$row]['@attributes']['Unearmarked'];
      $totalDonations += $listContribution[$row]['@attributes']['Total'];

      $country_name = $donor_name;
      while (preg_match('#(.*)\((.*?)\)(.*)#', $country_name, $group)) {
        $country_name = $group[1] . ' ' . $group[3];
      }
      $country_name = trim($country_name);

      if (isset($earmarked_donors[$donor_name])) {
        $earmarked_donors[$donor_name]['total'] += $listContribution[$row]['@attributes']['Total'];
      }
      else {
        $earmarked_donors[$donor_name] = array(
          'id' => $donorID,
          'total' => $listContribution[$row]['@attributes']['Total'],
          'earmarked' => $listContribution[$row]['@attributes']['Earmarked'],
          'unearmarked' => $listContribution[$row]['@attributes']['Unearmarked'],
          'name' => $donor_name,
          'country_name' => $country_name,
        );
      }
    }
    ksort($earmarked_donors);
  }

  $openingBalance = round($openingBalance);

  $data = [
    'year' => $year,
    'iso3' => $iso3,
    'totalRequirements' => $totalRequirements,
    'totalDonations' => $totalDonations,
    'openingBalance' => $openingBalance,
    'totalUnearmarked' => $totalUnearmarked,
    'earmarked_donors' => $earmarked_donors,
  ];

  file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
}

function GetCostPlanBreakDown($year) {
  $name = 'GetCostPlanBreakDown';
  $query = [
    'WebMethod' => $name,
    'ProjectGroupID' => 192,
    'year' => $year,
  ];

  $filename = 'projects_with_year';
  $filename .= '_' . $year . '.json';
  $result = getDataFromApi('WebServiceCaller.aspx', $query);

  $xml = simplexml_load_string($result);
  $result = $xml->xpath('//ProjectCostPlan');

  $data = [];
  foreach ($result as $item) {
    if (count($item->children()) == 0) {
      $data[] = [
        'id' => (string) $item['ID'],
        'name' => (string) $item['Name'],
        'year' => $year,
      ];
    }
  }

  file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
}

function getDataFromApi(string $path, array $query) {
  $endpoint = 'https://oct.unocha.org/';

  // Construct full URL.
  $fullUrl = $endpoint . $path . '?' . http_build_query($query);

  try {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $fullUrl);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      $body = curl_exec($ch);
      curl_close($ch);
  }
  catch (\Exception $exception) {
      throw $exception;
  }

  $results = $body;

  return $results;
}

main();
