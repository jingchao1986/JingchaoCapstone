import csv
import pandas as pd

df1 = pd.read_csv("/Users/user/Documents/GIS/Capstone/DataMe/combines.csv")
df2 = pd.read_csv("/Users/user/Documents/GIS/Capstone/DataMe/H2_stationsGeo.csv")

#change the field name "station1" to "name" before running the code

df3 = df1.merge(df2.drop_duplicates(subset=['name']), how='left')
df3.to_csv("/Users/user/Documents/GIS/Capstone/DataMe/leftjoin1.csv",index=False)

print('finished')

#change the "ZIP", "lat","lon","geom" to "ZIP1""lat1", "lon1", "geom1"