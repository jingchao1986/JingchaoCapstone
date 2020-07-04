#the script uses Esri build-in user to login, has certain limits for large size data
#usually use this script for csv files that have less than 1000 rows
from datetime import datetime
import pandas as pd
from arcgis.gis import GIS
import arcgis.network as network
import time

start_time = time.time()

gis = GIS("https://asu.maps.arcgis.com", client_id= "DYpgDVdyGdLKpRJI")
print("Successfully logged in as: " + gis.properties.user.username)
#python esri API username and ID

my_gis = gis

# if error occur: Method 'properties' has no 'helperServices' memberpylint(no-member)
#add Python for VSCode, and add Python Extension Pack
route_service_url = my_gis.properties.helperServices.route.url
route_service = network.RouteLayer(route_service_url, gis=my_gis)

#Remember that ESRI API can take no more than 1000 points at a time.
df = pd.read_csv(r'/Users/user/Documents/GIS/Capstone/junedata/test1.csv')
df['TravelTime'], df['Distance'] = None, None

route_layer = network.RouteLayer(route_service_url, gis=my_gis)

for idx, values in df.iterrows():
    stops = '{0},{1}; {2},{3}'.format(values['lon1'], values['lat1'], values['lon2'], values['lat2'])
    try:
        result = route_layer.solve(stops=stops)['routes']['features'][0]['attributes']
        df.loc[idx, 'TravelTime'] = result['Total_TravelTime']
        df.loc[idx, 'Distance'] = result['Total_Kilometers']
    except:
        print("no route at pair" +" "+ str(idx))
df.to_csv(r'/Users/user/Documents/GIS/Capstone/junedata/test2.csv')

#append new data to the exsiting file
#with open('/Users/user/Documents/GIS/Capstone/junedata/test3.csv', 'a') as f:
#    df.to_csv(f, header=False)

print('finished')
print("--- This calculation took %s seconds ---" % (time.time() - start_time))
