DELIMITER //
CREATE TRIGGER validate_author_reward_per_copy
    BEFORE INSERT
    ON edition
    FOR EACH ROW

    IF NEW.author_reward_copy>50 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Author reward per copy must be less than 50.';
    END IF//

DELIMITER ;