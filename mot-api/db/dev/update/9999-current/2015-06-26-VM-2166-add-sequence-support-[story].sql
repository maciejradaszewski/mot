drop table if exists ctrl_sequence;
create table ctrl_sequence (
  id                int unsigned not null auto_increment PRIMARY KEY,
  sequence_name     varchar(50) NOT NULL,
  code              varchar(10) NOT NULL COMMENT  'Unique code for the app to identify the desired sequence',
  prefix_str        varchar(50) DEFAULT NULL COMMENT 'An optional string to prefix to the sequence',
  padding           tinyint unsigned default 0 COMMENT 'Optional padding for zero-padded strings. Use 0 for no padding',
  sequence_number   int unsigned NOT NULL DEFAULT 0 COMMENT 'The actual sequence value used to generate the sequence',
  increment_val     smallint NOT NULL DEFAULT 1 COMMENT 'The increment value to add to the sequence',
  min_val           int unsigned not null DEFAULT 0 COMMENT 'The minimum value for the sequence',
  max_val           int unsigned not null COMMENT 'The maximum value for the sequence',
  `created_by`      INT UNSIGNED NOT NULL,
  `created_on`      TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_modified_by` INT UNSIGNED NOT NULL,
  `last_modified_on` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `version`         INT UNSIGNED NOT NULL DEFAULT 1,
  UNIQUE KEY uk_ctrl_sequence_code (code),
  KEY `ix_ctrl_sequence_created_by` (`created_by`),
  KEY `ix_ctrl_sequence_last_modified_by` (`last_modified_by`),
  CONSTRAINT `fk_ctrl_sequence_created_by_person_id` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_ctrl_sequence_last_modified_by_person_id` FOREIGN KEY (`last_modified_by`) REFERENCES `person` (`id`)
) COMMENT = 'Sequences used by the application: use the sp_sequence(:code) stored procedure to get the next sequence to use';


INSERT INTO ctrl_sequence VALUES (
    NULL,
    'AE Reference',
    'AEREF',
    'B',
    6,   -- padding
    0,   -- sequence_number you want to start with minus 1
    1,   -- increment
    0,   -- min
    4294967295, -- max
    2,   -- created by
    CURRENT_TIMESTAMP(6),
    2,
    CURRENT_TIMESTAMP(6),
    1
);

drop PROCEDURE IF EXISTS sp_sequence;

delimiter //

create DEFINER = 'root'@'127.0.0.1' procedure sp_sequence (
  IN in_seq_code varchar(10)
)
  NOT DETERMINISTIC
  READS SQL DATA
  MODIFIES SQL DATA
  SQL SECURITY INVOKER
  BEGIN

    DECLARE l_prefix varchar(50);
    DECLARE l_padding tinyint unsigned;
    DECLARE l_seq bigint;
    DECLARE l_increment smallint;
    DECLARE l_padded varchar(50) DEFAULT '';
    DECLARE l_min int unsigned;
    DECLARE l_max int unsigned;
    DECLARE l_out_sequence varchar(100) DEFAULT '';
    DECLARE l_message_text varchar(100) DEFAULT '';
    DECLARE sequence_not_found CONDITION FOR SQLSTATE '45000';
    DECLARE minimum_reached CONDITION FOR SQLSTATE '45001';
    DECLARE maximum_reached CONDITION FOR SQLSTATE '45002';

    START TRANSACTION;
      select prefix_str, padding, sequence_number, increment_val, min_val, max_val
      into l_prefix, l_padding, l_seq, l_increment, l_min, l_max
      from ctrl_sequence where code = in_seq_code FOR UPDATE;

      -- Check the sequence_code exists. sequence_number is not null so if we have a null
      -- it means no result set was returned.
      if (l_seq IS NULL) THEN
        set l_message_text = concat_ws('', 'Sequence "', in_seq_code, '" not found');
        SIGNAL sequence_not_found SET MESSAGE_TEXT = l_message_text;
      end if;

      -- increment the sequence number
      set l_seq = l_seq + l_increment;

      -- handle minimum value
      -- this signal is only generated as the l_seq datatype is larger in both directions!  This won't work if the
      -- db has a signed bigint sequence (you will get an out-of-range error instead).
      if (l_seq < l_min) then
        set l_message_text = concat_ws('', 'Sequence minimum value reached: ', l_min);
        SIGNAL minimum_reached SET MESSAGE_TEXT = l_message_text;
      end if;

      -- handle max
      if (l_seq > l_max) then
        set l_message_text = concat_ws('', 'Sequence maximum value reached: ', l_max);
        SIGNAL maximum_reached SET MESSAGE_TEXT = l_message_text;
      end if;

      -- deal with padding
      if (l_padding > 0) then

        -- If the padding to set to be less than the actual number then correct this, as the number should not
        -- be truncated just because the sequence grew bigger than the padding.
        if (l_padding < length(l_seq)) then
          set l_padding = length(l_seq);
        end if;

        -- Put it all together
        set l_padded = lpad(l_seq, l_padding, '0');
      else
        -- no padding requested.
        set l_padded = l_seq;
      end if;

      -- and write the *used* sequence number back out
      UPDATE ctrl_sequence
        set sequence_number = l_seq,
            last_modified_by = 0,
            last_modified_on = CURRENT_TIMESTAMP(6),
            version = version + 1
        where code = in_seq_code;

      -- return the new sequence string as a result set so the app can get the result in the same call.
      set l_out_sequence = concat(coalesce(l_prefix, ''), l_padded);
      select l_out_sequence as sequence from dual;

    COMMIT;
  END;

//
delimiter ;
