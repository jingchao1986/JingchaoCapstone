# JingchaoCapstone
Fuel stations for alternative fueling vehicles are not as adequate as those for the regular vehicles, and they also lack the support of online mapping tools that help the drivers to find feasible routes without running out of fuel. To help with these issues addressed, Dr. Michael Kuby and his group created an online mapping tool prototype for alternative fueling vehicle routing with range and refueling stations in 2014. This tool will help the drivers to find the shortest feasible path from the origin to the destination. The users can put in start and end location, driving range of the vehicle, initial fuel level, and travel type in the interface. Then the tool will generate the feasible shortest path with the stations for refueling and will show the path description on the webpage in a yellow box. However, the data of the stations and pre-processed distance between all pairs of the station points have not been updated since 2014.

The change of the alternative fueling station since 2014, including the hydrogen and CNG (compressed natural gas) station, may not be significant in its quantity. However, the discrepancy of data will cause inaccuracy of the generated path and stations, then affects the user experience of the tool. For example, the user could be led to a nonexistent station because the station may have been removed in 2020. So I updated the database using the 2020 data of the stations, regenerated the station networks, and updated some of the front-end part.

The project includes collecting data sets in 2020 for the AFV stations, using scripting to generate shortest distance pairs, and updating the user interface of the app. Due to the requirement of large computational power, the distance between all pairs of nodes is pre-generated by python programming and Esri’s ArcGIS online geocoding service. I regenerated the distance of the nodes because of the update of the station-location data will affect the result of the previously generated road network (OD Matrix).
	
The capstone project involves the tools and technologies such as Python, PostgreSQL, PostGIS extension, etc. 

In addition, I use the Bootstrap library to make the web page more responsive and changed some of the colors and fonts. I also experimented on replacing the Google Map base map with the Esri base map, and visualize the stations point data using the AGOL feature service.
