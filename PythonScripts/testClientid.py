from datetime import datetime
import pandas as pd
from arcgis.gis import GIS
import arcgis.network as network
import time

gis = GIS("https://asu.maps.arcgis.com", client_id= "DYpgDVdyGdLKpRJI")
print("Successfully logged in as: " + gis.properties.user.username)
#python esri API username and ID

token = gis._con.token
print(token)