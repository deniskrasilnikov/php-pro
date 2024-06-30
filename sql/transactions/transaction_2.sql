# TRANSACTION 2
START TRANSACTION;

update book set name = CONCAT(name, ' T2') WHERE id = 10096;
# update book set name = CONCAT(name, ' T2') WHERE id = 398;

COMMIT;