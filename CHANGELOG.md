# CHANGELOG


## UNRELEASED

- Refactors Connectors (interface changed)
- Adds PHP 5.4 dependency
- Closes down API
- Updates tests

### Added

- `update` method in `Process` updates the payload

### Altered

- `isState` method is renamed to `checkState` (in both `Supervisor` and `Process`)
- Process must wait for the response of stop in `restart`

### Removed

- Ability to pass `Process` object into `Supervisor` method calls: in case of different connector instances it could have led to an inconsistent state
- Ability to construct `Process` object from name, use `Process::get` instead
- Memory usage check form `Process`


## 2.0.1 (released 2014-07-13)

- Updates dependencies


## 2.0.0 (released 2014-07-13)

- Removes HTTP client parts
- Uses Guzzle as HTTP Client by default
- Adds Zend XML-RPC connector
- Adds `AbstractNamedSection`
- Event and Event Listener restructure
- Removes API from `Supervisor`
- Major test changes (unit, functional)


## 1.2.0 (released 2014-05-06)

- Code coverage improved
- Unit tests improved
- Travis build improved
- Minor fixes


## 1.1.1 (released 2014-01-29)

- Unit tests moved into Test namespace
- Fixed license issues


## 1.1.0 (released 2014-01-20)

- Added Symfony Commands
- Added Symfony Console Application
- Added Event Listeners
- Added `isLocal` to Connectors and Supervisor
- Added `SupervisorException`
- Added `RpcInterfaceSection`
- Improved unit tests
- Fixed several bugs
- Removed `ResponseException`


## 1.0.0 (released 2014-01-17)

- Initial release
- Supervisor
- Configuration
