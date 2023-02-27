<!--- BEGIN HEADER -->
# Changelog

All notable changes to this project will be documented in this file.
<!--- END HEADER -->

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

