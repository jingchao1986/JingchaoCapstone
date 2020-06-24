import pandas as pd

df = pd.read_csv(r'/Users/user/Documents/GIS/Capstone/2020data/H_stations.csv')
df['TravelTime'], df['Distance'] = None, None

from itertools import combinations

df_new = pd.DataFrame(list(combinations(df.StationName, 2)), columns=['stations1', 'stations2'])
df_new.to_csv("/Users/user/Documents/GIS/Capstone/DataMe/combines.csv",index=False)

print("combined")
