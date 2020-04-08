/**
 * Module dependencies.
 */
var express = require('express');
var cookieParser = require('cookie-parser');
var compress = require('compression');
var session = require('express-session');
var bodyParser = require('body-parser');
var logger = require('morgan');
var errorHandler = require('errorhandler');
var methodOverride = require('method-override');
var multer  = require('multer');
var cors = require('cors');

var _ = require('lodash');
var MongoStore = require('connect-mongo')(session);
var flash = require('express-flash');
var path = require('path');
var mongoose = require('mongoose');
var passport = require('passport');
var expressValidator = require('express-validator');
var connectAssets = require('connect-assets');
var favicon = require('serve-favicon');
require('node-jsx').install({ harmony: true });

/**
 * Controllers (route handlers).
 */
var homeController = require('./controllers/home');
var userController = require('./controllers/user');
var searchController = require('./controllers/search');
var apiController = require('./controllers/api');
var videoController = require('./controllers/video');

/**
 * API keys and Passport configuration.
 */
var secrets = require('../config/secrets');
var passportConf = require('../config/passport');

/**
 * Create Express server.
 */
var app = express();

/**
 * Connect to MongoDB.
 */
mongoose.connect(secrets.db);
mongoose.connection.on('error', function() {
  console.error('MongoDB Connection Error. Please make sure that MongoDB is running.');
});

/**
 * Express configuration.
 */
app.set('port', process.env.PORT || 3000);

app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'jade');
//app.use(compress());

app.use(connectAssets({
  paths: [
    path.join(__dirname, 'public'),
    path.join(__dirname, 'public/css'),
    path.join(__dirname, 'public/js'),
    path.join(__dirname, 'public/fonts'),
    path.join(__dirname, 'public/js/pages')
  ]
}));

app.use(logger('dev'));
app.use(cors());
//app.use(bodyParser.json());
//app.use(bodyParser.urlencoded({ extended: true }));
//app.use(multer({ dest: path.join(__dirname, 'uploads') }));
//app.use(expressValidator());
//app.use(methodOverride());
//app.use(cookieParser());

app.use(session({
  resave: true,
  saveUninitialized: true,
  secret: secrets.sessionSecret,
  store: new MongoStore({ url: secrets.db, autoReconnect: true })
}));

app.use(passport.initialize());
app.use(passport.session());

app.use(flash());

app.use(function(req, res, next) {
  res.locals.user = req.user;
  next();
});

app.use(express.static(path.join(__dirname, 'public'), { maxAge: 31557600000 }));
app.use(favicon(path.join(__dirname, '/public/favicon.ico')));

/**
 * Primary app routes.
 */

/** Home */
app.get('/', homeController.index);

/** User */
app.get('/login', userController.getLogin);
app.post('/login', userController.postLogin);
app.get('/logout', userController.logout);
app.get('/forgot', userController.getForgot);
app.post('/forgot', userController.postForgot);
app.get('/reset/:token', userController.getReset);
app.post('/reset/:token', userController.postReset);
app.get('/signup', userController.getSignup);
app.post('/signup', userController.postSignup);

/** Account */
app.get('/account', passportConf.isAuthenticated, userController.getAccount);
app.post('/account/profile', passportConf.isAuthenticated, userController.postUpdateProfile);
app.post('/account/password', passportConf.isAuthenticated, userController.postUpdatePassword);
app.post('/account/delete', passportConf.isAuthenticated, userController.postDeleteAccount);
app.get('/account/unlink/:provider', passportConf.isAuthenticated, userController.getOauthUnlink);
app.post('/account/profile/facebook/additionalScopes', passportConf.isAuthenticated, userController.postAdditionalFbScopes);

/** Elasticearch video API */
app.get('/api/video/recommended', apiController.getRecommendedVideos);
app.get('/api/video', apiController.getVideoByQuery);
app.get('/api/video/:id', apiController.getVideoById);
app.get('/api/video/map', apiController.getVideoByMap); // TODO: use query params
app.get('/api/facebook/likes', apiController.getFacebookLikes); // TODO: use query params

/** Search */
app.get('/search', searchController.index);

/** Video */
app.get('/video/:id', videoController.index);


/**
 * OAuth authentication routes. (Sign in)
 */

/** Facebook auth and scopes */
app.get('/auth/facebook', passport.authenticate('facebook', {
  scope: [
    'email',
    'user_location',
    'user_friends',
    'user_likes'
  ]
}));

/** Facebook callback */
app.get('/auth/facebook/callback', passport.authenticate('facebook', { failureRedirect: '/login' }), function(req, res) {
  res.redirect('/');
});

/** Google auth and scopes */
app.get('/auth/google', passport.authenticate('google', { scope: 'profile email' }));

/** Google callback */
app.get('/auth/google/callback', passport.authenticate('google', { failureRedirect: '/login' }), function(req, res) {
  res.redirect(req.session.returnTo || '/');
});

/**
 * Error Handler.
 */
app.use(errorHandler());

/**
 * Start Express server.
 */
app.listen(app.get('port'), function() {
  console.log('Express server listening on port %d in %s mode', app.get('port'), app.get('env'));
});

module.exports = app;
