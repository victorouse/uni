---
layout: page
title: Architecture overview
permalink: /architecture/
exclude_from_nav: true
---
The Globetrotter application architecture consists of two main components: the web application and the video database. These components are decoupled from each other in order for the video database to be reusable between web and mobile applications.

#### Backend
Both video and web applications are served using [Node](https://nodejs.org/) and [Express](http://expressjs.com/). The key difference being that the video database is merely provides RESTful endpoints to abstract search queries. 

#### Frontend
The web application renders static HTML using [Jade](http://jade-lang.com/) as a templating engine, and dynamic content using [React](http://facebook.github.io/react/). These two frameworks are used in combination with each other to produce an isomorphic application. Isomorphic means that application code is shared between the client and the server, enabling faster page loads and increased performance when the DOM is manipulated. This approach in combination with [Backbone](http://backbonejs.org/) enables the server to handle the initial data when the page is loaded, and then lets the client handle queries to the user and video API which propagate to a Backbone collection. 

#### User Storage
For user profile storage, a [MongoDB](http://www.mongodb.org/) database is used, providing a user schema which is populated upon user registration. [Passport](http://passportjs.org/) is used as an authentication strategy for local registration, and utilising the oAuth 2.0 protocol for Google+ and Facebook registration. In doing so, a token is issued from the selected provider which can then be used to retrieve user data from their social profiles. 

Additionally, users may “link” a social profile post-registration which will pull and merge their social profile with their existing profile. In doing so, the system is able to collate as much information as possible for a user in order to better understand their persona, producing better search results and improving a user’s experience. 

#### Video Storage
Video storage is purely implemented using [Elasticsearch](https://www.elastic.co/products/elasticsearch). Since the Virtual Tourist dataset is static, the dataset is imported from the MongoDB collection directly into Elasticsearch. There are numerous benefits to this approach, namely the powerful query tools provided by the Elasticsearch API and the field indexing which provides fast query throughput.   

#### Video Player
The video player utilises [VideoJS](http://www.videojs.com/), which is a HTML5 video player library. This library supports a variety of different video encodings, most importantly “mp4” and “flv” which are the most commonly seen in the Virtual Tourist database. The benefit of using this library is that videos can be streamed directly from their Virtual Tourist URL, removing the need to locally store video data and have a dedicated video server.

 


