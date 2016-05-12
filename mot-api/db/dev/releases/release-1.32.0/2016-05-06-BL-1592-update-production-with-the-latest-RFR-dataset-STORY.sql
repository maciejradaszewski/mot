SET @rfr_id_56942 = 8371;
SET @rfr_id_56946 = 8372;
SET @rfr_id_57928 = 8725;
SET @rfr_id_57938 = 8728;
SET @rfr_id_58849 = 10108;
SET @rfr_id_58855 = 10109;

SET @lang_en = (SELECT `id` FROM `language_type` WHERE `code` = "EN");
SET @lang_cy = (SELECT `id` FROM `language_type` WHERE `code` = "CY");

UPDATE `rfr_language_content_map`
SET `inspection_manual_description` = 'The calculated service brake efficiency is too low'
WHERE `rfr_id` = @rfr_id_56942 AND `language_type_id` = @lang_en;

UPDATE `rfr_language_content_map`
SET `inspection_manual_description` = 'The calculated parking brake efficiency is too low'
WHERE `rfr_id` = @rfr_id_56946 AND `language_type_id` = @lang_en;

UPDATE `rfr_language_content_map`
SET `advisory_text` = 'dim ond bron yn bodloni effeithiolrwydd. Ymddengys fod y system frecio angen ei haddasu neu ei hatgyweirio.'
WHERE `rfr_id` = @rfr_id_57928 AND `language_type_id` = @lang_cy;

UPDATE `rfr_language_content_map`
SET `advisory_text` = 'dim ond bron yn bodloni effeithiolrwydd. Ymddengys fod y system frecio angen ei haddasu neu ei hatgyweirio.'
WHERE `rfr_id` = @rfr_id_57938 AND `language_type_id` = @lang_cy;

UPDATE `rfr_language_content_map`
SET `name` = 'fel nad yw trawst \'cicio i fyny\' yn weladwy ar y sgrin'
WHERE `rfr_id` = @rfr_id_58849 AND `language_type_id` = @lang_cy;

UPDATE `rfr_language_content_map`
SET `name` = 'fel nad yw trawst \'cicio i fyny\' yn weladwy ar y sgrin'
WHERE `rfr_id` = @rfr_id_58855 AND `language_type_id` = @lang_cy;