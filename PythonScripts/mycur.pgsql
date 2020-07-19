create or replace function getPair() RETURNS TEXT AS $$
DECLARE mycur CURSOR FOR SELECT * from h2combines WHERE ST_DWithin(geom1, geom2, 643738;
rec RECORD;
BEGIN
OPEN mycur;
LOOP
FETCH mycur INTO rec;
EXIT WHEN NOT FOUND;
END LOOP;
mycur;
RETURN rec;
END; $$
LANGUAGE plpgsql;