import csv
import pandas as pd

df1 = pd.read_csv("/Users/user/Documents/GIS/Capstone/DataMe/leftjoin1.csv")
df2 = pd.read_csv("/Users/user/Documents/GIS/Capstone/DataMe/H2_stationsGeo.csv")

#Change the "name" back to "stations1",change the field name "station2" to "name", 
#delete "gid" column
#before running the code

df3 = df1.merge(df2.drop_duplicates(subset=['name']), how='left')
df3.to_csv("/Users/user/Documents/GIS/Capstone/DataMe/leftjoin2.csv",index=False)

print('finished')

#change the "ZIP", "lat","lon","geom" to "ZIP2", "lat2", "lon2", "geom2"