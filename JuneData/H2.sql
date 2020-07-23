
# create distinct id pairs of all H2 stations.

CREATE TABLE h2stations(id serial NOT NULL, name VARCHAR(100),address VARCHAR(100),city VARCHAR(100),state VARCHAR(50),zip VARCHAR(50), lat double precision, lon double precision);

\COPY h2stations(name, address, city, state, zip,lat,lon) FROM '/Users/user/Documents/GIS/Capstone/Junedata/h2_stations.csv' DELIMITERS ',' CSV HEADER;

ALTER TABLE h2stations ADD COLUMN geog geography(POINT,4326);
UPDATE h2stations SET geog = ST_GeogFromText('SRID=4326;POINT(' || lon || ' ' || lat || ')');

CREATE Table h2stationsCopy AS SELECT * FROM h2stations;

CREATE TABLE h2pairs AS 
SELECT h2stations.id id1, h2stationsCopy.id id2, st_distance(h2stations.geog, h2stationsCopy.geog) FROM h2stations, h2stationsCopy WHERE st_dwithin(h2stations.geog, h2stationsCopy.geog, 643738);

CREATE TABLE h2pairsDistinct AS
SELECT DISTINCT ON (h2pairs.st_distance) id1, id2 FROM h2pairs WHERE id1 != id2;

#alternative way:
SELECT h2stations.id id1, h2stationsCopy.id id2, st_distance(h2stations.geog, h2stationsCopy.geog) FROM h2stations, h2stationsCopy WHERE st_dwithin(h2stations.geog, h2stationsCopy.geog, 643738) and h2stations.id < h2stationscopy.id;

#join the lat lon in h2stations to the id pairs

CREATE TABLE leftjoin1 AS SELECT h2pairsdistinct.id1, h2pairsdistinct.id2,h2stations.lat, h2stations.lon FROM h2pairsdistinct LEFT JOIN h2stations ON h2stations.id = h2pairsdistinct.id1;

CREATE TABLE leftjoin2 AS SELECT leftjoin1.id1, leftjoin1.id2, leftjoin1.lat lat1, leftjoin1.lon lon1, h2stations.lat lat2, h2stations.lon lon2 FROM leftjoin1 LEFT JOIN h2stations ON  leftjoin1.id2 = h2stations.id;

 CREATE TABLE h2network AS select * from leftjoin2 order by id1;

\copy (SELECT * FROM leftjoin2) TO '/Users/user/Documents/GIS/Capstone/Junedata/leftjoin2.csv' CSV HEADER;

\copy (SELECT * FROM h2network) TO '/Users/user/Documents/GIS/Capstone/Junedata/h2network.csv' CSV HEADER;

CREATE TABLE hstations(gid serial NOT NULL, stationnam VARCHAR(100),address VARCHAR(100),city VARCHAR(100),state VARCHAR(50),zip VARCHAR(50), stationpho VARCHAR(100),access VARCHAR(100), access2 VARCHAR(100), lat double precision, lon double precision, id int);

#create geometry
ALTER TABLE hstations ADD COLUMN geom geometry(POINT,4326);
UPDATE hstations SET geom = ST_SetSRID(ST_MakePoint(lon, lat), 4326);


//afv cng tables 
create table network(source int, target int, cost real);
CREATE TABLE stations(gid serial NOT NULL, stationnam VARCHAR(100),address VARCHAR(100),city VARCHAR(100),state VARCHAR(50),zip VARCHAR(50), stationpho VARCHAR(100),access VARCHAR(100), access2 VARCHAR(100), lat double precision, lon double precision, id int);

\copy network(source, target, cost) from '/Users/user/Documents/GIS/Capstone/Junedata/cng/cngnetworkmile.csv' DELIMITERS ',' CSV HEADER;
#create geometry

\copy stations(stationnam, address,city,state, zip, stationpho,access,access2,lat, lon,id) from '/Users/user/Documents/GIS/Capstone/Junedata/cng/cng_stations.csv' DELIMITERS ',' CSV HEADER;

ALTER TABLE stations ADD COLUMN geom geometry(POINT,4326);
UPDATE stations SET geom = ST_SetSRID(ST_MakePoint(lon, lat), 4326);