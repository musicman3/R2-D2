# R2-D2 PHP AutoRouter

### Project installation:
`composer require musicman3/r2-d2`

### System requirements: 
  - OS Unix, Linux or Windows
  - Apache Web Server >= 2.4 or Nginx >= 1.17
  - PHP >= 8.3

Project R2-D2 is an automatic router that automatically creates routing for new objects added to the project, eliminating the need for constant configuration when adding new objects. It was created primarily for the eMarket project: https://github.com/musicman3/eMarket

The autorouter only allows you to configure the main routing branches, while R2-D2 automatically collects all object data and routes it. This eliminates the need to route each new object.

You simply add new objects (classes), and they will be automatically configured according to the configuration file structure. For autorouting to be feasible, we must adhere to the minimum placement requirements for new objects, which are also set in the configuration file.
