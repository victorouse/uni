/**
 * IMPORTANT  IMPORTANT  IMPORTANT  IMPORTANT  IMPORTANT  IMPORTANT  IMPORTANT
 *
 * You should never commit this file to a public repository on GitHub!
 * All public code on GitHub can be searched, that means anyone can see your
 * uploaded secrets.js file.
 *
 * I did it for your convenience using "throw away" API keys and passwords so
 * that all features could work out of the box.
 *
 * Use config vars (environment variables) below for production API keys
 * and passwords. Each PaaS (e.g. Heroku, Nodejitsu, OpenShift, Azure) has a way
 * for you to set it up from the dashboard.
 *
 * Another added benefit of this approach is that you can use two different
 * sets of keys for local development and production mode without making any
 * changes to the code.

 * IMPORTANT  IMPORTANT  IMPORTANT  IMPORTANT  IMPORTANT  IMPORTANT  IMPORTANT
 */

module.exports = {

  db: process.env.MONGODB || 'mongodb://globe:trotter@localhost:27017/globetrotter_db',

  elasticRestEndpoint: process.env.ELASTIC_REST_ENDPOINT || 'http://localhost:3001',

  sessionSecret: process.env.SESSION_SECRET || 'Your Session Secret goes here',

  mandrill: {
    user: process.env.MANDRILL_USER || 'hackathonstarterdemo',
    password: process.env.MANDRILL_PASSWORD || 'E1K950_ydLR4mHw12a0ldA'
  },


  facebook: {
    clientID: process.env.FACEBOOK_ID || '1553498951590839',
    clientSecret: process.env.FACEBOOK_SECRET || '9f2d7d1af7fc0f0a78c530559e698767',
    callbackURL: '/auth/facebook/callback',
    passReqToCallback: true
  },


  google: {
    clientID: process.env.GOOGLE_ID || '697488017681-brmbdr81bh6u7mrf8r6a52avj535qg3a.apps.googleusercontent.com',
    clientSecret: process.env.GOOGLE_SECRET || 'uW-82xsPVnmw86q0m_oU3xgO',
    callbackURL: '/auth/google/callback',
    apiKey: 'AIzaSyCqkTpFcT0c3L8XhqsgkAZ9CTAsC-jQDBA',
    passReqToCallback: true
  }
};
