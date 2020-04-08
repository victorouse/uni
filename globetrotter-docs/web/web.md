---
layout: page
title: Web Application
permalink: /web/
exclude_from_nav: true
---
### Getting Started
The web application repository can be found here:

[https://victorouse@bitbucket.org/cavr/web-app.git](https://victorouse@bitbucket.org/cavr/web-app.git)

Clone this repository and run `npm install` to install the dependencies located in the `package.json`. Once installed, make sure that the Virtual Tourist API is running and run `node server.js` to serve the web application. Express will start a web server located at `http://localhost:3000`.

### User Profiles
To get started with the Globetrotter web application, a user can sign up with a local account, Facebook or Google+. This is available after clicking “Getting Started” from the landing page. API keys for Google+ and Facebook can be found in the `config/secrets.js` file.

Once registered, a user profile will be stored in the MongoDB database, according to the following schema:

{% gist victorouse/1abd96326377b33dc218 %}
<br />

By default, only the public profile is populated at registration. A user can optionally allow the application access to extended scopes through the user management page. 

### User Management Page
The user management page is where a user can manage their profile. Basic information that can be pulled from the public profile is displayed here and can be updated by the user if it contains erroneous information. 

![Profile information]({{ site.url }}/assets/profile_information.png)
<br />

Extra scopes can be requested from social providers to enable the system to utilise more user data for the purpose of tailoring search results. This feature depends on the social accounts that are linked to the profile. 

![Additional scopes]({{ site.url }}/assets/scopes.png)
<br />

The user can optionally link one or more social account to their user profile. 

![Link accounts]({{ site.url }}/assets/link.png)
<br />

### Home Page
The homepage is the first point of contact the user has with the application. The goal here is to immediately immerse the user in context-aware results collated from their geolocation and user profile.

![Home page]({{ site.url }}/assets/home_page.png)
<br />

### Search Page
The search page is the crux of the application. It enables the user to search for videos in a variety of ways with results being tailored from their user profile. 

![Search page]({{ site.url }}/assets/search.png)
<br />

#### Search Tools
In addition to a pure text-search, search tools are provided for the user to query the video database in a variety of ways.

#### Map
The map is used for filtering queries by geolocation. A user can select the “circle” option to draw a container on the map where they would like to filter results from. Multiple containers can be drawn to filter from multiple geolocations. 

![Map]({{ site.url }}/assets/map.png)

<br />
<hr />
<br />

![Geolocation query]({{ site.url }}/assets/geolocation_query.png)

#### Image Upload
TODO.

### Results
Results are dynamically retrieved in a “search as you type” manner which queries the video API on partial user input. This enables the user to see results being filtered in order to give them more options when considering video results. 

![Partial search query]({{ site.url }}/assets/aus.png)

<br />
<hr />
<br />

![Full query]({{ site.url }}/assets/australia.png)
<br />

When a video is selected, a modal will appear with the video player. In this modal, the user can rate the video they have selected as well as save the video to their profile. Contextual information such as the time spent watching the selected video is stored without the user being aware of such processes. 

Additionally, a user can click the “link” icon in order to get a permanent link to the video they have selected. This feature enables users to hotlink and share videos across platforms. 

<br />
![Video modal]({{ site.url }}/assets/modal.png)




 
