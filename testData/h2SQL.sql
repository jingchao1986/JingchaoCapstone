{\rtf1\ansi\ansicpg1252\cocoartf2512
\cocoatextscaling0\cocoaplatform0{\fonttbl\f0\fswiss\fcharset0 Helvetica;\f1\fnil\fcharset0 Menlo-Regular;\f2\fnil\fcharset134 PingFangSC-Regular;
}
{\colortbl;\red255\green255\blue255;\red0\green0\blue0;}
{\*\expandedcolortbl;;\csgray\c0;}
\margl1440\margr1440\vieww10800\viewh8400\viewkind0
\pard\tx720\tx1440\tx2160\tx2880\tx3600\tx4320\tx5040\tx5760\tx6480\tx7200\tx7920\tx8640\pardirnatural\partightenfactor0

\f0\fs24 \cf0 #make a copy of table\
\pard\tx560\tx1120\tx1680\tx2240\tx2800\tx3360\tx3920\tx4480\tx5040\tx5600\tx6160\tx6720\pardirnatural\partightenfactor0

\f1\fs22 \cf2 \CocoaLigature0 CREATE Table h2stationsCopy AS SELECT * FROM h2stations;\
SELECT 46\
\
#make all pairs of stations\
SELECT h2stations.gid, h2stationsCopy.gid, st_distance(h2stations.geom::geography, h2stationsCopy.geom::geography) distance,st_dwithin(h2stations.geom::geography, h2stationsCopy.geom::geography, 643738) FROM h2stations, h2stationsCopy;\
\
#make all pairs of stations within 400 miles\
#It is important to understand the data types in PostGIS methods such as ST_distance and ST_DWithn. When putting geometry as arguments, it returns degrees of lat Lon. One can use \'93::geography\'94 to change the data type from geometry to geography. Then it will return distance in meter which are more appropriate for the project.\
Geometry uses Cartesian measurement and geography uses geodetic(spherical curve of the earth).\
https://postgis.net/workshops/postgis-intro/geography.html\
\
\
\
#select distinct pairs of station ids because using st_distance and st_DWithin created many duplicates in the records.\
\
CREATE TABLE h2pairsDistinct AS\
SELECT DISTINCT ON (h2pairs.st_distance) gid1, gid2 FROM h2pairs WHERE gid1 != gid2;\
\

\f2 \'b7\'bd\'b7\'a8\'d7\'dc\'bd\'e1\'a3\'ba\'d7\'ee\'ba\'c3\'cf\'c8\'c1\'cb\'bd\'e2\'b9\'a4\'be\'df\'b5\'c4\'be\'df\'cc\'e5\'d3\'c3\'b7\'a8\'a3\'ac\'cd\'a8\'b9\'fd\'cb\'fc\'b5\'c4documentation\'a3\'ac\'d5\'e2\'d1\'f9\'bf\'c9\'d2\'d4\'bd\'da\'ca\'a1\'ca\'b1\'bc\'e4\'a3\'ac\'bc\'f5\'c9\'d9\'d7\'df\'cd\'e4\'c2\'b7\'a1\'a3\'b1\'c8\'c8\'e7\'d4\'c4\'b6\'c1ST_DWithin\'b5\'c4\'d3\'c3\'b7\'a8\'a3\'ac\'c0\'ed\'bd\'e2\'cb\'fc\'b5\'c4\'d4\'cb\'d7\'f7\'b7\'bd\'ca\'bd\'a3\'ac\'be\'cd\'bf\'c9\'d2\'d4\'b5\'c3\'b5\'bdall pairs of stations. \'d2\'f2\'ce\'aa\'cb\'fc\'bb\'e1\'d7\'d4\'b6\'af\'b1\'c8\'bd\'cf\'cb\'f9\'d3\'d0pair.}