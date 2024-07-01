create table edition_deleted
(
    id                 int unsigned auto_increment
        primary key,
    book_id            int unsigned                                 not null,
    publisher_id       int unsigned                                 not null,
    price              int unsigned       default '0'               not null,
    author_reward_base int unsigned       default '0'               not null,
    author_reward_copy int unsigned       default '0'               not null
);


DELIMITER //
CREATE TRIGGER delete_edition
    AFTER DELETE
    ON edition
    FOR EACH ROW

    INSERT INTO edition_deleted(id, book_id, publisher_id, price, author_reward_base, author_reward_copy)
    VALUES (OLD.id, OLD.book_id, OLD.publisher_id, OLD.price, OLD.author_reward_base, OLD.author_reward_copy)//

DELIMITER ;
