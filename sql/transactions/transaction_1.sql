# TRANSACTION 1
START TRANSACTION;
#
select * from book where author_id = 208;
#
do sleep(10);
#
update book set name = CONCAT(name, ' T1') WHERE id = 398;
#
select * from book where id = 398;
#
COMMIT;
