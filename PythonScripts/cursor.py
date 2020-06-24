import psycopg2
connection = psycopg2.connect(host = "localhost", database ="afv", user="postgres", password = "postgres")
cursor = connection.cursor()

cursor.execute("SELECT * from h2combines WHERE ST_DWithin(h2combines.geom1, h2combines.geom2, 643738)")

for row in cursor:
        print(row[0])

'''query = """SELECT * from h2combines WHERE ST_DWithin(h2combines.geom1, h2combines.geom2, 643738)"""


sql = "copy ({0}) to stdout with csv header".format(query)
with open("/Users/user/Documents/GIS/Capstone/cursor.csv", "w") as file:
    cursor.copy_expert(sql, file)'''

cursor.close()
