---
layout: page
title: Video Database
permalink: /video/
exclude_from_nav: true
---
A RESTful API wrapper has been built around the Virtual Tourist database to simplify queryies and utilise the power of Elasticsearch. The goal of the API is to provide a simple interface that can be shared amongst both web and mobile applications. 


### Getting Started

#### Installing Elasticsearch
The first step in deploying the API is to install Elasticsearch. Installation instructions can be found [here](https://www.elastic.co/downloads/elasticsearch). Additionally, it is helpful to  install the Elasticsearch Head plugin which provides a web-based interface to help navigate the database. Install instructions for the plugin can be found [here](http://mobz.github.io/elasticsearch-head/).

#### Importing the Collection
As the database provided has been given as a MongoDB collection, to import this database into Elasticsearch requires exporting the documents as JSON objects and then importing them via the Elasticsearch HTTP PUT method.

In order to enable geolocation queries, the collection schema for videos and photos must be modified to work with Elasticsearch. To do so, follow the instructions detailed below:

{% gist victorouse/1f9552c9afd73f3ea6ec %}
<br />

### Video Schema
The document schema for the database is now as follows:

{% gist victorouse/dfe3601a0b70c33d4cb9 %}
<br />

Once the schema has been modified, clone the [Elaster](https://github.com/likeastore/elaster) repository and copy the following config into `config/index.js`. Elaster is a small tool used to export MongoDB collections into JSON objects, mapping the schema types into Elasticsearch compatible types and finally batch importing the documents to Elasticsearch.

{% gist victorouse/2aeeecc663a61d470f82 %}
<br />


### Installation
The repository for the Virtual Tourist API can be found here:

[https://github.com/victorouse/virtual-tourist-elastic](https://github.com/victorouse/virtual-tourist-elastic)

Clone this repository and run `npm install` to install the dependencies located in the `package.json`. Once installed, make sure that Elasticsearch is running and run `node server.js` to serve the API. Express will start a web server located at `http://localhost:3001`.

The web application can now query the Elasticsearch database. 


### REST Endpoints
Now that the API is running, the following endpoints are available via a HTTP GET request.

##### Get video by ID
`$ curl http://localhost:3001/video/:id`

##### Get videos by query string 
`$ curl http://localhost:3001/video/q?video=<query>`

##### Get videos by geolocation
`$ curl http://localhost:3001/video/:lon/:lat/:dist`

##### Get videos by multiple geolocations
`$ curl http://localhost:3001/video/gq?=<geoquery>`


### Extending the API
To write your own queries using Elasticsearch, modify the file located in `controllers/video.js`.
Within this controller are methods defined by:

```
exports.<method> = function(req, res) { .. }
```

The `client` variable defined at the beginning of the controller exposes the [Elastic.js](http://www.fullscale.co/elasticjs/) 
library which is used to construct queries. This library is a wrapper for the DSL query language that Elasticsearch natively
uses, defining methods and parameters that compile down to the DSL query syntax. 

The documentation for Elastic.js can be found [here](http://docs.fullscale.co/elasticjs/).





