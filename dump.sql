CREATE TABLE Hotel (IdH VARCHAR(2) PRIMARY KEY, Prix INT, Distance INT, NbEt INT);
INSERT INTO Hotel VALUES("H1", 30, 800, 3);
INSERT INTO Hotel VALUES("H2", 35, 800, 3);
INSERT INTO Hotel VALUES("H3", 60, 400, 3);
INSERT INTO Hotel VALUES("H4", 60, 100, 5);
INSERT INTO Hotel VALUES("H5", 50, 300, 4);
INSERT INTO Hotel VALUES("H6", 60, 300, 4);


CREATE View Ideal
AS
SELECT MIN(Prix) Min_Prix, MIN(Distance) Min_Distance, MAX(NbEt) Max_NbEt
FROM Hotel;

CREATE VIEW Somme
AS
SELECT MIN(Prix) Min_Prix, MIN(Distance) Min_Distance, MAX(NbEt) Max_NbEt
FROM Hotel;

CREATE View Hotel_Norm
AS
SELECT H.IdH, Truncate(M.Min_Prix/H.Prix, 3) Prix_Norm, Truncate(M.Min_Distance/H.Distance, 3) Distance_Norm, Truncate(H.NbEt/M.Max_NbEt, 3) NbEt_Norm
FROM Hotel H, Somme M;

SELECT * FROM Hotel_Norm;

CREATE VIEW Hotel_Pond
as
SELECT IdH, TRUNCATE(0.5 * Prix_Norm, 3) Prix_Pond, TRUNCATE(0.25 * Distance_Norm, 2) Distance_Pond, TRUNCATE(0.25 * NbEt_Norm, 3) NbEt_Pond
FROM Hotel_Norm;

SELECT * FROM Hotel_Pond;

CREATE VIEW Hotel_Score
as
SELECT IdH, Prix_Pond, Distance_Pond, NbEt_Pond, TRUNCATE((Prix_Pond+Distance_Pond+NbEt_Pond), 3) Score
FROM Hotel_Pond;

SELECT * FROM Hotel_Score;

SELECT H.IdH, H.Prix, H.Distance, H.NbEt, S.Score
FROM Hotel H, Hotel_Score S
WHERE H.IdH = S.IdH
ORDER BY S.Score Desc;
