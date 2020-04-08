# COMS4200 Group F - PoxVis network visualisation tool

This is the repository for the PoxVis web app. 

## Installation

Clone the repo and `cd` into the folder, then run `npm install`.


## Hot Module Replacement development server

``` text
# start the webpack-dev-server in HMR mode
npm run hot-dev-server
# wait for the first compilation

# in another terminal/console
# start the node.js server in development mode
npm run start-dev

# open this url in your browser
http://localhost:8080/
```


## Production compilation and server

``` text
# build the client bundle and the prerendering bundle
npm run build

# start the node.js server in production mode
npm run start

# open this url in your browser
http://localhost:8080/
```
