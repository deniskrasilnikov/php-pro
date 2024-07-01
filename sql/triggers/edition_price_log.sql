CREATE TABLE edition_price_log
(
    edition_id int unsigned not null primary key,
    old_price  int unsigned not null,
    new_price  int unsigned not null,
    updated_by varchar(100)  not null,
    updated_at timestamp    not null
);


DELIMITER //
CREATE TRIGGER edition_price_log
    AFTER UPDATE
    ON edition
    FOR EACH ROW

    INSERT INTO edition_price_log(edition_id, old_price, new_price, updated_by, updated_at)
    VALUES (NEW.id, OLD.price, NEW.price, (SELECT USER()), NOW())//

DELIMITER ;