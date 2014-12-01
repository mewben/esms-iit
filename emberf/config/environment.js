/* jshint node: true */

module.exports = function(environment) {
  var ENV = {
    modulePrefix: 'emberf',
    environment: environment,
    baseURL: '/',
    locationType: 'auto',
    CORS: [],
    EmberENV: {
      FEATURES: {
        // Here you can enable experimental features on an ember canary build
        // e.g. 'with-controller': true
      }
    },

    APP: {
      // Here you can pass flags/options to your application instance
      // when it is created
    }
  };

  if (environment === 'development') {
    // ENV.APP.LOG_RESOLVER = true;
    ENV.APP.LOG_ACTIVE_GENERATION = true;
    // ENV.APP.LOG_TRANSITIONS = true;
    // ENV.APP.LOG_TRANSITIONS_INTERNAL = true;
    ENV.APP.LOG_VIEW_LOOKUPS = true;

    ENV.HOST = 'http://server2.dev:8000';
    ENV.API = 'api/v2';
    ENV.ROOTURL= '/';
    ENV.DOMAIN = 'http://server2.dev:8000';
    ENV.CORS = ['http://server2.dev:8000'];
  }

  if (environment === 'test') {
    // Testem prefers this...
    ENV.baseURL = '/';
    ENV.locationType = 'auto';

    // keep test console output quieter
    ENV.APP.LOG_ACTIVE_GENERATION = false;
    ENV.APP.LOG_VIEW_LOOKUPS = false;

    ENV.APP.rootElement = '#ember-testing';

    ENV.HOST = 'http://server2.dev:8000';
    ENV.API = 'api/v2';
    ENV.ROOTURL= '/';
    ENV.DOMAIN = 'http://server2.dev:8000';
    ENV.CORS = ['http://server2.dev:8000'];
  }

  if (environment === 'production') {
    ENV.HOST = '';
    ENV.API = 'api/v2';
    ENV.ROOTURL= '/';
    ENV.DOMAIN = '/';
  }

  return ENV;
};
