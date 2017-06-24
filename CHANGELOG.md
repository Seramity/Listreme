# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [1.2.0-alpha] - 2017-06-24
### Added
- "Remember me" login function
- Ability to disable auth registration
### Changed
- Removed multiple routes from "Auth" controller and split them up into different controllers (SignInController, SignUpController, etc.)
### Fixed
- Fixed apache indexing assets folder (user avatars, etc.)

## [1.1.1-alpha] - 2017-06-19
### Fixed
- Fixed lists positions so a user cannot change it to a larger number than the total number of lists they have
- Fixed lowercase function on the wrong user input on editing lists

## [1.1.0-alpha] - 2017-06-05
### Added
- Uploaded user avatars
- Image helper class
    - Upload and resizing
    - Resize animated gifs
- User picture settings page
### Changed
- Lists: force category inputs in lowercase
- Replaced Markdown library with CommonMark

## [1.0.0-alpha] - 2017-05-30
First development release.
