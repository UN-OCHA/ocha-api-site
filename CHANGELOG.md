<!--- BEGIN HEADER -->
# Changelog

All notable changes to this project will be documented in this file.
<!--- END HEADER -->

## [1.2.0](https://github.com/UN-OCHA/ocha-api-site/compare/v0.2.1...v1.2.0) (2023-12-12)

### Features

* Add endpoint to get providers [#OHA-42](https://https://humanitarian.atlassian.net/browse/OHA-42) ([bb66f0](https://github.com/UN-OCHA/ocha-api-site/commit/bb66f06cec38bfdc61ec36f6b7b9d7b9573ed3ef))
* Add figure_id ([b889f9](https://github.com/UN-OCHA/ocha-api-site/commit/b889f9eaa9c226fc8f3c030bbabb151abf8dc6da))
* Add unit to figures [#OHA-42](https://https://humanitarian.atlassian.net/browse/OHA-42), [#OHA-42](https://https://humanitarian.atlassian.net/browse/OHA-42) ([fb98a5](https://github.com/UN-OCHA/ocha-api-site/commit/fb98a531b10ad9e82566bf227b087b790117d229), [de2cda](https://github.com/UN-OCHA/ocha-api-site/commit/de2cdaa12648d50b6422272ddc8ca725c5bba2a9))
* Add versioning to Ocha Presences [#UNO-794](https://https://humanitarian.atlassian.net/browse/UNO-794) ([9a7a9e](https://github.com/UN-OCHA/ocha-api-site/commit/9a7a9e5e103d6031d7bb9642ee587b58f0e24b45))
* Allow user to update email and webhook ([7ce8a6](https://github.com/UN-OCHA/ocha-api-site/commit/7ce8a645a58ad1eaf1cd69fe51c67e96595d5a74), [92de3a](https://github.com/UN-OCHA/ocha-api-site/commit/92de3a763d5b1fbea4be998d622074e1d8abb77f))
* Force iso3 to be lowercase [#OHA-47](https://https://humanitarian.atlassian.net/browse/OHA-47) ([c197af](https://github.com/UN-OCHA/ocha-api-site/commit/c197af240b18071b94bde604b5d157915a812371))
* Id consistency [#OHA-48](https://https://humanitarian.atlassian.net/browse/OHA-48) ([53354f](https://github.com/UN-OCHA/ocha-api-site/commit/53354fe6a787c7d2c02b9c1bf9abefb86d4d5230))
* OCHA presence and countries ([3488d6](https://github.com/UN-OCHA/ocha-api-site/commit/3488d679860f36c0c7a0141c3b5cfca7ac06c09c))
* OCHA presence endpoint ([231525](https://github.com/UN-OCHA/ocha-api-site/commit/2315256b4d6c4ae601b1ac91bf9f7721b6888c3d))
* Use manytomany for ocha presense and countries ([19764f](https://github.com/UN-OCHA/ocha-api-site/commit/19764f527747a22bb547b975346e2603ff839de7))

### Bug Fixes

* Ah! there you are! ([35e61c](https://github.com/UN-OCHA/ocha-api-site/commit/35e61c4fc4633702919b20543309201cc6f055f0))
* Except if the literal line is missing from .env the image build fails. ([e3343b](https://github.com/UN-OCHA/ocha-api-site/commit/e3343b0c58640bf674388eb28b3894216950892d))
* How about we emit a *valid* Referrer-Policy header. ([12c6f4](https://github.com/UN-OCHA/ocha-api-site/commit/12c6f419d69bbf60d8736aa379ce9755417d4d12))
* Un-break the Dockerfile. ([d0877b](https://github.com/UN-OCHA/ocha-api-site/commit/d0877bb24ef489a6e310ed276844ff51c0b6923e))
* Update the lock file. ([607ba5](https://github.com/UN-OCHA/ocha-api-site/commit/607ba5b80fa96c109e35e239bf21ac363dc19a6a))

### Chores

* Adapt tests to run locally ([ee461a](https://github.com/UN-OCHA/ocha-api-site/commit/ee461aab0134de666cbde9f84788755cbdf6b70e))
* Add api-platform ([86f16c](https://github.com/UN-OCHA/ocha-api-site/commit/86f16c831aabc8ea549e1571a4e8aad125d36522), [1c47c3](https://github.com/UN-OCHA/ocha-api-site/commit/1c47c344aaddb6acb94b2cf1682fd04de3cc9d15))
* Add a test for the me Patch endpoint. ([be3c4c](https://github.com/UN-OCHA/ocha-api-site/commit/be3c4cb86f693f2e5f4f7b7273e5981c1f3a0d59))
* Add command to manage roles ([3e0370](https://github.com/UN-OCHA/ocha-api-site/commit/3e0370aca302c544f267d25fc7d35ac1dd7d4b8a))
* Add endpoint to manage external ids ([02745f](https://github.com/UN-OCHA/ocha-api-site/commit/02745f0990f92ca773c65791f695f05aa1cc620e), [0fec90](https://github.com/UN-OCHA/ocha-api-site/commit/0fec90212ee319918f3de70c470e2ff2cd5fd3c0))
* Add info ([2a0d52](https://github.com/UN-OCHA/ocha-api-site/commit/2a0d52d8959c8739ec1e2b942c6da4bd279cfe04))
* Allow DELETE ([dafe30](https://github.com/UN-OCHA/ocha-api-site/commit/dafe30110f5952b407436d306771be2fa09338d4))
* Allow PATCH ([17aa5b](https://github.com/UN-OCHA/ocha-api-site/commit/17aa5b8ab3aea7927d6184bd92324b5cc56a0df7))
* Allow token when adding users ([e6715e](https://github.com/UN-OCHA/ocha-api-site/commit/e6715efb88c213863f6635704100704d15231e58))
* Allow update on figureId and externalId ([6b3086](https://github.com/UN-OCHA/ocha-api-site/commit/6b3086f92b92f05ca6fbee361c4f726578294399))
* Archived defaults to FALSE ([9d4054](https://github.com/UN-OCHA/ocha-api-site/commit/9d405412fa9bb541b5bb5538546c1914f762b89b))
* Bump composer PHP requirement. ([ddc5c1](https://github.com/UN-OCHA/ocha-api-site/commit/ddc5c1cd04a8623ed18e921bb506cecc06811783))
* Bump doctrine/orm to see if that fixes database issues during testing. ([3f43a0](https://github.com/UN-OCHA/ocha-api-site/commit/3f43a08b9c753cb5c9a41860907aa5416dc9ae38))
* Bump MariaDB in the tests. ([b838ad](https://github.com/UN-OCHA/ocha-api-site/commit/b838adcc3076e949cd2cf54e87b212b644c71791))
* Changelog ([af6acf](https://github.com/UN-OCHA/ocha-api-site/commit/af6acf4710f42483aa1466834a03312aef16401b))
* Cleanup the build steps. Disable legacy non-buildkit commands. ([8797b5](https://github.com/UN-OCHA/ocha-api-site/commit/8797b590baf2d8a77b2bf13b477e37020119fa8a))
* Cleanup the build steps. No need for a builder and php-sodium is in our base image. ([3ad6c7](https://github.com/UN-OCHA/ocha-api-site/commit/3ad6c75b258ff24aa9a01baa367f8c76f226a3c4))
* Composer updates ([9b39cc](https://github.com/UN-OCHA/ocha-api-site/commit/9b39cc9800bdb806e145090f449579503b971d94))
* Correct docs [#OHA-39](https://https://humanitarian.atlassian.net/browse/OHA-39) ([6c0357](https://github.com/UN-OCHA/ocha-api-site/commit/6c03573c89d11f5c4af301ec44efd3c2fc2bccfe))
* Deprecations ([55dd09](https://github.com/UN-OCHA/ocha-api-site/commit/55dd09f39e9e5374c84242c8887604281bc4e0bb))
* Do not use this .env file to override the DATABASE_URL that hautelook should get from the docker env. Ouch. ([749d7d](https://github.com/UN-OCHA/ocha-api-site/commit/749d7dd9951c1328b12b78d5f3ac531474405dce))
* Drop version tables first ([176bb8](https://github.com/UN-OCHA/ocha-api-site/commit/176bb841af6cf567135f9a22252c9f0a4ac95bde))
* Fail hard if data property is abused ([6020fd](https://github.com/UN-OCHA/ocha-api-site/commit/6020fd10090a73180a6e62c6ec5ae6cfc9bce7ac))
* Fix serializer [#OHA-42](https://https://humanitarian.atlassian.net/browse/OHA-42) ([1c295f](https://github.com/UN-OCHA/ocha-api-site/commit/1c295fbc72b6bea721d6fd784ec9c3718244b9df))
* Fix the tests. ([df07aa](https://github.com/UN-OCHA/ocha-api-site/commit/df07aaa8c0e20f83285ec6090f99a13970529830))
* Force jsonld for denormalize ([99d8bc](https://github.com/UN-OCHA/ocha-api-site/commit/99d8bc6aa10f5ebe0e72a92c45239e7e26e7bb38))
* Ignore deprecations ([1a9b74](https://github.com/UN-OCHA/ocha-api-site/commit/1a9b747abd999deb8716a5573b4f43a3a13f4f4d))
* Make prod log to file, not stderr, so the file can go into ELK. ([3e720c](https://github.com/UN-OCHA/ocha-api-site/commit/3e720cbd9780e55b582592207dcedc005ea453fb))
* Make sure external id exists ([87a0eb](https://github.com/UN-OCHA/ocha-api-site/commit/87a0eb3702d66c8c521c3fe4743ad64abd06b3c8))
* More yaks, this time api-plaform. ([acfea8](https://github.com/UN-OCHA/ocha-api-site/commit/acfea84ccefe044ab7ae918dd58d594d09f4e2e6))
* Oh, run periodic updates for the API! ([cb4579](https://github.com/UN-OCHA/ocha-api-site/commit/cb457943ed11c50b42b1521b74ad08c0caaa0867))
* Promote externalId to property ([e0f5a5](https://github.com/UN-OCHA/ocha-api-site/commit/e0f5a5516277e8521c51da40636e61556d3dad59), [4267a2](https://github.com/UN-OCHA/ocha-api-site/commit/4267a2b36e0ad97833b7bb48e5bc30aa3783a941))
* Re-add monolog ([a58690](https://github.com/UN-OCHA/ocha-api-site/commit/a5869095b7b2cfb9e80a2755d8caa44e2068890b))
* Remove the unused incorrect TAG env var from the Makefile. ([636eb6](https://github.com/UN-OCHA/ocha-api-site/commit/636eb66feb6327b46ad2f5d9fa00d61ee2a2102b))
* Seriously, composer calidate? ([5a81a4](https://github.com/UN-OCHA/ocha-api-site/commit/5a81a40093b70fdd3fa84a26dee4381ee2636ae3))
* Switch slack channel to vars, so we can see it.
 ([b5d997](https://github.com/UN-OCHA/ocha-api-site/commit/b5d997dd1df8abeb46d8725224540cbd58305716))
* Test version endpoint ([1bce1b](https://github.com/UN-OCHA/ocha-api-site/commit/1bce1bf0605b1817440d324292756f737f66d13c))
* Try PHP 8.2 and the associated newer Elastic APM. ([056dbe](https://github.com/UN-OCHA/ocha-api-site/commit/056dbe6931b9a2335e950229bf9f6770aff5e95f))
* Un-drop this change (to make composer work locally). ([dadb49](https://github.com/UN-OCHA/ocha-api-site/commit/dadb49818a7c2e9e26ce5bfd210cba7e7da4b6b5))
* Update all outdated symfony/* api-platform/* packages. ([91c398](https://github.com/UN-OCHA/ocha-api-site/commit/91c398a069851134cccb42e4dd8f38694570b6c5), [757bfb](https://github.com/UN-OCHA/ocha-api-site/commit/757bfb6473fdc869b8deb5931ea294f9223b5b96))
* Update one package to get composer.lock up to date ([9eb484](https://github.com/UN-OCHA/ocha-api-site/commit/9eb484f2610c3ff5a1a22a1d846a41753eedc31b))
* Update packages and remove deprecated package ([29f0e7](https://github.com/UN-OCHA/ocha-api-site/commit/29f0e76828cb090ddd373f9d1607879ffa4e6c5c))
* Use build in PUT ([0f0d41](https://github.com/UN-OCHA/ocha-api-site/commit/0f0d4125775570006007956d9e252738487f1ede))
* Use `composer audit` for security updates and configure it to report on abandoned things. ([3460e9](https://github.com/UN-OCHA/ocha-api-site/commit/3460e9b53cd25242aea1329ee17b75d3c34c0b07))
* Use the Node16 AWS credential action. ([7be16c](https://github.com/UN-OCHA/ocha-api-site/commit/7be16c3c34077d88883107266abef31ac622f132))
* Version endpoint ([8edec6](https://github.com/UN-OCHA/ocha-api-site/commit/8edec6cb7fb14f916f3d4e428296bc1b093eb520), [ad2b77](https://github.com/UN-OCHA/ocha-api-site/commit/ad2b77ba6280af23a2b9ed4393bfa40a0db9ba93))

##### Deps

* Bump api-platform/core from 3.1.2 to 3.1.3 ([f059bc](https://github.com/UN-OCHA/ocha-api-site/commit/f059bcd2ea6a78303afaf847172622a2234282ba))

## [1.0.1](https://github.com/UN-OCHA/ocha-api-site/compare/v0.3.0...v1.0.1) (2023-06-26)

### Features

* Add endpoint to get providers [#OHA-42](https://https://humanitarian.atlassian.net/browse/OHA-42) ([bb66f0](https://github.com/UN-OCHA/ocha-api-site/commit/bb66f06cec38bfdc61ec36f6b7b9d7b9573ed3ef))
* Add figure_id ([b889f9](https://github.com/UN-OCHA/ocha-api-site/commit/b889f9eaa9c226fc8f3c030bbabb151abf8dc6da))
* Add unit to figures [#OHA-42](https://https://humanitarian.atlassian.net/browse/OHA-42), [#OHA-42](https://https://humanitarian.atlassian.net/browse/OHA-42) ([fb98a5](https://github.com/UN-OCHA/ocha-api-site/commit/fb98a531b10ad9e82566bf227b087b790117d229), [de2cda](https://github.com/UN-OCHA/ocha-api-site/commit/de2cdaa12648d50b6422272ddc8ca725c5bba2a9))
* Allow string data [#OHA-30](https://https://humanitarian.atlassian.net/browse/OHA-30) ([f9c674](https://github.com/UN-OCHA/ocha-api-site/commit/f9c6742b2580e74e275b8218d2b67ba664e3e266))
* Allow user to update email and webhook ([7ce8a6](https://github.com/UN-OCHA/ocha-api-site/commit/7ce8a645a58ad1eaf1cd69fe51c67e96595d5a74), [92de3a](https://github.com/UN-OCHA/ocha-api-site/commit/92de3a763d5b1fbea4be998d622074e1d8abb77f))
* Force iso3 to be lowercase [#OHA-47](https://https://humanitarian.atlassian.net/browse/OHA-47) ([c197af](https://github.com/UN-OCHA/ocha-api-site/commit/c197af240b18071b94bde604b5d157915a812371))
* Id consistency [#OHA-48](https://https://humanitarian.atlassian.net/browse/OHA-48) ([53354f](https://github.com/UN-OCHA/ocha-api-site/commit/53354fe6a787c7d2c02b9c1bf9abefb86d4d5230))
* OCHA presence and countries ([3488d6](https://github.com/UN-OCHA/ocha-api-site/commit/3488d679860f36c0c7a0141c3b5cfca7ac06c09c))
* OCHA presence endpoint ([231525](https://github.com/UN-OCHA/ocha-api-site/commit/2315256b4d6c4ae601b1ac91bf9f7721b6888c3d))
* Use manytomany for ocha presense and countries ([19764f](https://github.com/UN-OCHA/ocha-api-site/commit/19764f527747a22bb547b975346e2603ff839de7))

### Chores

* 27-02-2023 prep release ([d580de](https://github.com/UN-OCHA/ocha-api-site/commit/d580de906be7454cc7bf4963586096fb265be232), [04debe](https://github.com/UN-OCHA/ocha-api-site/commit/04debe676a0f516d836480a38f8e09accf81ac75))
* Adapt tests to run locally ([ee461a](https://github.com/UN-OCHA/ocha-api-site/commit/ee461aab0134de666cbde9f84788755cbdf6b70e))
* Add a test for the me Patch endpoint. ([be3c4c](https://github.com/UN-OCHA/ocha-api-site/commit/be3c4cb86f693f2e5f4f7b7273e5981c1f3a0d59))
* Add command to manage roles ([3e0370](https://github.com/UN-OCHA/ocha-api-site/commit/3e0370aca302c544f267d25fc7d35ac1dd7d4b8a))
* Add endpoint to manage external ids ([02745f](https://github.com/UN-OCHA/ocha-api-site/commit/02745f0990f92ca773c65791f695f05aa1cc620e), [0fec90](https://github.com/UN-OCHA/ocha-api-site/commit/0fec90212ee319918f3de70c470e2ff2cd5fd3c0))
* Add info ([2a0d52](https://github.com/UN-OCHA/ocha-api-site/commit/2a0d52d8959c8739ec1e2b942c6da4bd279cfe04))
* Allow DELETE ([dafe30](https://github.com/UN-OCHA/ocha-api-site/commit/dafe30110f5952b407436d306771be2fa09338d4))
* Allow PATCH ([17aa5b](https://github.com/UN-OCHA/ocha-api-site/commit/17aa5b8ab3aea7927d6184bd92324b5cc56a0df7))
* Allow token when adding users ([e6715e](https://github.com/UN-OCHA/ocha-api-site/commit/e6715efb88c213863f6635704100704d15231e58))
* Allow update on figureId and externalId ([6b3086](https://github.com/UN-OCHA/ocha-api-site/commit/6b3086f92b92f05ca6fbee361c4f726578294399))
* Archived defaults to FALSE ([9d4054](https://github.com/UN-OCHA/ocha-api-site/commit/9d405412fa9bb541b5bb5538546c1914f762b89b))
* Cleanup the build steps. Disable legacy non-buildkit commands. ([8797b5](https://github.com/UN-OCHA/ocha-api-site/commit/8797b590baf2d8a77b2bf13b477e37020119fa8a))
* Cleanup the build steps. No need for a builder and php-sodium is in our base image. ([3ad6c7](https://github.com/UN-OCHA/ocha-api-site/commit/3ad6c75b258ff24aa9a01baa367f8c76f226a3c4))
* Composer updates ([9b39cc](https://github.com/UN-OCHA/ocha-api-site/commit/9b39cc9800bdb806e145090f449579503b971d94))
* Correct docs [#OHA-39](https://https://humanitarian.atlassian.net/browse/OHA-39) ([6c0357](https://github.com/UN-OCHA/ocha-api-site/commit/6c03573c89d11f5c4af301ec44efd3c2fc2bccfe))
* Fail hard if data property is abused ([6020fd](https://github.com/UN-OCHA/ocha-api-site/commit/6020fd10090a73180a6e62c6ec5ae6cfc9bce7ac))
* Fix serializer [#OHA-42](https://https://humanitarian.atlassian.net/browse/OHA-42) ([1c295f](https://github.com/UN-OCHA/ocha-api-site/commit/1c295fbc72b6bea721d6fd784ec9c3718244b9df))
* Make prod log to file, not stderr, so the file can go into ELK. ([3e720c](https://github.com/UN-OCHA/ocha-api-site/commit/3e720cbd9780e55b582592207dcedc005ea453fb))
* Make sure external id exists ([87a0eb](https://github.com/UN-OCHA/ocha-api-site/commit/87a0eb3702d66c8c521c3fe4743ad64abd06b3c8))
* Promote externalId to property ([e0f5a5](https://github.com/UN-OCHA/ocha-api-site/commit/e0f5a5516277e8521c51da40636e61556d3dad59), [4267a2](https://github.com/UN-OCHA/ocha-api-site/commit/4267a2b36e0ad97833b7bb48e5bc30aa3783a941))
* Update one package to get composer.lock up to date ([9eb484](https://github.com/UN-OCHA/ocha-api-site/commit/9eb484f2610c3ff5a1a22a1d846a41753eedc31b))
* Use build in PUT ([0f0d41](https://github.com/UN-OCHA/ocha-api-site/commit/0f0d4125775570006007956d9e252738487f1ede))
* Use the Node16 AWS credential action. ([7be16c](https://github.com/UN-OCHA/ocha-api-site/commit/7be16c3c34077d88883107266abef31ac622f132))

##### Deps

* Bump api-platform/core from 3.1.2 to 3.1.3 ([f059bc](https://github.com/UN-OCHA/ocha-api-site/commit/f059bcd2ea6a78303afaf847172622a2234282ba))

## [0.3.0](https://github.com/UN-OCHA/ocha-api-site/compare/v0.2.0...v0.3.0) (2023-05-16)

### Features

* Add endpoint to get providers [#OHA-42](https://https://humanitarian.atlassian.net/browse/OHA-42) ([bb66f0](https://github.com/UN-OCHA/ocha-api-site/commit/bb66f06cec38bfdc61ec36f6b7b9d7b9573ed3ef))
* Add unit to figures [#OHA-42](https://https://humanitarian.atlassian.net/browse/OHA-42), [#OHA-42](https://https://humanitarian.atlassian.net/browse/OHA-42) ([fb98a5](https://github.com/UN-OCHA/ocha-api-site/commit/fb98a531b10ad9e82566bf227b087b790117d229), [de2cda](https://github.com/UN-OCHA/ocha-api-site/commit/de2cdaa12648d50b6422272ddc8ca725c5bba2a9))
* Allow string data [#OHA-30](https://https://humanitarian.atlassian.net/browse/OHA-30) ([f9c674](https://github.com/UN-OCHA/ocha-api-site/commit/f9c6742b2580e74e275b8218d2b67ba664e3e266))

### Chores

* 27-02-2023 prep release ([d580de](https://github.com/UN-OCHA/ocha-api-site/commit/d580de906be7454cc7bf4963586096fb265be232), [04debe](https://github.com/UN-OCHA/ocha-api-site/commit/04debe676a0f516d836480a38f8e09accf81ac75))
* Add info ([2a0d52](https://github.com/UN-OCHA/ocha-api-site/commit/2a0d52d8959c8739ec1e2b942c6da4bd279cfe04))
* Composer updates ([9b39cc](https://github.com/UN-OCHA/ocha-api-site/commit/9b39cc9800bdb806e145090f449579503b971d94))
* Fix serializer [#OHA-42](https://https://humanitarian.atlassian.net/browse/OHA-42) ([1c295f](https://github.com/UN-OCHA/ocha-api-site/commit/1c295fbc72b6bea721d6fd784ec9c3718244b9df))
* Use the Node16 AWS credential action. ([7be16c](https://github.com/UN-OCHA/ocha-api-site/commit/7be16c3c34077d88883107266abef31ac622f132))

##### Deps

* Bump api-platform/core from 3.1.2 to 3.1.3 ([f059bc](https://github.com/UN-OCHA/ocha-api-site/commit/f059bcd2ea6a78303afaf847172622a2234282ba))

## [0.2.1](https://github.com/UN-OCHA/ocha-api-site/compare/v0.2.0...v0.2.1) (2023-02-27)

### Features

* Allow string data [#OHA-30](https://https://humanitarian.atlassian.net/browse/OHA-30) ([f9c674](https://github.com/UN-OCHA/ocha-api-site/commit/f9c6742b2580e74e275b8218d2b67ba664e3e266))

## [0.1.2](https://github.com/UN-OCHA/ocha-api-site/compare/v0.1.1...v0.1.2) (2022-12-16)

### Features

* Allow endpoint and key as paramater ([b81292](https://github.com/UN-OCHA/ocha-api-site/commit/b81292e845dda4c8e35b68ba8f5403134f50d19c))
* Title ([bbc3c7](https://github.com/UN-OCHA/ocha-api-site/commit/bbc3c77d91445eeaca634312c0ca63ef38c04f43))
* Use our styling ([242efe](https://github.com/UN-OCHA/ocha-api-site/commit/242efeaa6219fa93364a8723bd67c45bcd26daed))

### Chores

* Add conventional changelog ([4eec73](https://github.com/UN-OCHA/ocha-api-site/commit/4eec73f0d04b46cecd929ecfd842232ea9f8a2fd))
* Get version from composer ([e9af11](https://github.com/UN-OCHA/ocha-api-site/commit/e9af119162ab31112e21963e17544ca89aacdc1c))

## [0.1.1](https://github.com/UN-OCHA/ocha-api-site/compare/v0.1.0...v0.1.1) (2022-12-13)

* Documentation changes.

## [0.1.0](https://github.com/UN-OCHA/ocha-api-site/compare/1d0e18a996900b1ac687bc9fa4b9bc94ed960c11...v0.1.0) (2022-12-13)

### Features

* ACAPS data [#OHA-18](https://https://humanitarian.atlassian.net/browse/OHA-18) ([58a787](https://github.com/UN-OCHA/ocha-api-site/commit/58a787903a908d33f78c2a560b2bcef131126c41))
* Add CERF data [#OHA-6](https://https://humanitarian.atlassian.net/browse/OHA-6) ([377274](https://github.com/UN-OCHA/ocha-api-site/commit/377274f607dc87c296c3f5d6c68372e39f84fba3))
* Allow owner to use PUT for insert and update ([bb74c9](https://github.com/UN-OCHA/ocha-api-site/commit/bb74c937ba1d4cb0cf1f549a8f1f4956ff002e84))
* Allow PUT for insert and update ([e67f49](https://github.com/UN-OCHA/ocha-api-site/commit/e67f49ae0280104b49ba08aaf607320706107996))
* CORS allow all ([0d7da7](https://github.com/UN-OCHA/ocha-api-site/commit/0d7da70ee62d319ff0f364a23f5f206268d2bf0d))
* CORS allow all, take 2 ([ea7e37](https://github.com/UN-OCHA/ocha-api-site/commit/ea7e37ab7841d0bff920583ee1c0271975658d23))
* Drop in the AWS SES mailer package, so production can send mail via SES. ([c35640](https://github.com/UN-OCHA/ocha-api-site/commit/c3564064bcb124ac18c9ba8ec4851d778d1b03c3))
* Extra fields ([d04f5e](https://github.com/UN-OCHA/ocha-api-site/commit/d04f5ea2701561bbe6ac141dacab1698e8696f90))
* Import IDPs ([54d70f](https://github.com/UN-OCHA/ocha-api-site/commit/54d70f907120ba4bd8637bec50206280d095f8df))
* Install the Elastic APM module with templated config. ([416e58](https://github.com/UN-OCHA/ocha-api-site/commit/416e583f463625fe8b2264ed584957e4c6333cf8))
* Remove provider [#OHA-26](https://https://humanitarian.atlassian.net/browse/OHA-26) ([0fd26f](https://github.com/UN-OCHA/ocha-api-site/commit/0fd26fc4279ac8a3a1a95948ec4f13f3239857b3))
* Require app-name ([e026d7](https://github.com/UN-OCHA/ocha-api-site/commit/e026d72561682ab11aaa5889d7b4a77912bc0b44))
* Serve n8n templates ([99dcd7](https://github.com/UN-OCHA/ocha-api-site/commit/99dcd7c28ac30c8bab9fd5743cce5f7523e24869))
* Use env variable to control the animated spider and the profiler. ([35ec77](https://github.com/UN-OCHA/ocha-api-site/commit/35ec77b193f1e05aceb111f8f60999fefa2877be))

### Bug Fixes

* A bit of cleanup. ([b114a5](https://github.com/UN-OCHA/ocha-api-site/commit/b114a598f11aabc14d58aeb89d6ae94ed2400ee6), [0d498a](https://github.com/UN-OCHA/ocha-api-site/commit/0d498a2872b90df2925bfef4447111e6555780ed))
* Adopt CD ToC component for in-page nav ([a655eb](https://github.com/UN-OCHA/ocha-api-site/commit/a655eb9a9bbcd62e955dd2cfae254f8eb65c91b0))
* Better in-page anchor behavior ([473fb8](https://github.com/UN-OCHA/ocha-api-site/commit/473fb87fcda3c18fc4a29df2f496493e7e2d8152))
* Cast our strings to booleans. ([b8b43d](https://github.com/UN-OCHA/ocha-api-site/commit/b8b43db604fc4db9be86f3c8011fae3821e667c0))
* Clean up nav, code blocks ([1df635](https://github.com/UN-OCHA/ocha-api-site/commit/1df6352c3b93af016373fff047ab139738a8576e))
* Copy config files to an actual dir, not a made-up one. ([129f21](https://github.com/UN-OCHA/ocha-api-site/commit/129f21d498a3c0ce9855c6023dda47491c563ad8))
* Do not add the ACAO: * header. The app will handle this. ([d01f7b](https://github.com/UN-OCHA/ocha-api-site/commit/d01f7b2571ea8fdd5ddbc6382fa0b46f2ed47bf3))
* Favicon ([785c08](https://github.com/UN-OCHA/ocha-api-site/commit/785c0875b9d5169fe263e9a101aeb7ddff18dfaf))
* If nelmio will not handle this in Symfony, just set headers in nginx. ([f701ed](https://github.com/UN-OCHA/ocha-api-site/commit/f701ed8188ae414f2b361ccff070e467394488c9))
* Make back-to-top links stand out more ([459638](https://github.com/UN-OCHA/ocha-api-site/commit/459638ec698693d28c6a205083daf92a3cad7eaf))
* Proper contact email for docs ([4edb98](https://github.com/UN-OCHA/ocha-api-site/commit/4edb98dc0afdc42c586e98188cc24768c9760abb))
* Remove relative paths to CSS ([9d991d](https://github.com/UN-OCHA/ocha-api-site/commit/9d991d71e98e371a3331eafde21b780835b14a92))
* Run compsoer instal before testing. ([17d6b0](https://github.com/UN-OCHA/ocha-api-site/commit/17d6b015997ec05306834baa4239f5b23ba54341))
* Shove the profiler flag into the correct yaml file. ([d9069c](https://github.com/UN-OCHA/ocha-api-site/commit/d9069c9e44a72fb3a4d457eaece42d25ec8eb11d))
* Specify related OCHA Services ([80c6e9](https://github.com/UN-OCHA/ocha-api-site/commit/80c6e9025690b05a8c0c6063c9d8252f91cad4f6))
* Style the website with CD ([971180](https://github.com/UN-OCHA/ocha-api-site/commit/971180b217e9961dc79acacfd70cd5db2b8282bf))
* Title/content cleanup ([623790](https://github.com/UN-OCHA/ocha-api-site/commit/623790e5e9d90a20ab7ce4eb62ec3ee69d33db2a))
* Update the lock file, which is fully required? ([26fd3b](https://github.com/UN-OCHA/ocha-api-site/commit/26fd3bce7be11f02bf4a4b5e2822b59bf968799c))

### Chores

* Add sodium ([b0fe33](https://github.com/UN-OCHA/ocha-api-site/commit/b0fe3316eec150cf8b67bb7656582808ef9dc0a2))
* Add testing ([2a002f](https://github.com/UN-OCHA/ocha-api-site/commit/2a002f2d57cc1a3356c1897bc865462e687be128))
* Clean migrations ([e0d3ea](https://github.com/UN-OCHA/ocha-api-site/commit/e0d3ea32e0cbd22884600c40e97c888f5d89a9f4))
* Command for providers ([0a9417](https://github.com/UN-OCHA/ocha-api-site/commit/0a941777b9c57783e35d86086942497fb0e8499a))
* Copy the Symfony builder stuff from ROLAC SARA and then customise it. ([fedb05](https://github.com/UN-OCHA/ocha-api-site/commit/fedb0550c32bb97122854135e9f9611482090195))
* Do not run scripts ([dd9ded](https://github.com/UN-OCHA/ocha-api-site/commit/dd9deda1480645f763535f4c4bd866a801d4afdc))
* Env without db ([68252a](https://github.com/UN-OCHA/ocha-api-site/commit/68252af552afc411b55186509abaac96d481c784))
* Remove extra headers [#OPS-8873](https://https://humanitarian.atlassian.net/browse/OPS-8873) ([c03f59](https://github.com/UN-OCHA/ocha-api-site/commit/c03f5914e08ce8dd61652bd5a476735432434d06))
* Typos ([983df1](https://github.com/UN-OCHA/ocha-api-site/commit/983df1ac61ade9963969cf6414350f844ba9ccc1))
* Update packages ([154e54](https://github.com/UN-OCHA/ocha-api-site/commit/154e543491a2dcb7269bfd70fd58667615bd9810))

