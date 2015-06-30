package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.datasource.Text;

public class VerifyCertificateDetails {

    public String getTitle(String parsedText) {
        int lastIndex = parsedText.indexOf(Text.TEXT_MOT_TEST_NUMBER);
        int startIndex = parsedText.indexOf(Text.TEXT_NOT_VALID);
        return parsedText.substring(startIndex + Text.TEXT_NOT_VALID.length() + 1, lastIndex - 1);
    }

    public String getVT32Title(String parsedText) {
        int startIndex = parsedText.lastIndexOf(Text.TEXT_NOT_VALID);
        int lastIndex = parsedText.lastIndexOf(Text.TEXT_MOT_TEST_NUMBER);
        return parsedText.substring(startIndex + Text.TEXT_NOT_VALID.length() + 1, lastIndex - 1);
    }

    public String getVT20TestNumberMakeModel(String parsedText) {
        int startIndex = parsedText.indexOf(Text.TEXT_MOT_TEST_NUMBER);
        int lastIndex = parsedText.indexOf(Text.TEXT_VEHICLE_REGISTRATION_MARK);
        return parsedText.substring(startIndex, lastIndex);
    }

    public String getVT30TestNumber(String parsedText) {
        int startIndex = parsedText.lastIndexOf(Text.TEXT_MOT_TEST_NUMBER);
        int lastIndex = parsedText.lastIndexOf(Text.TEXT_VEHICLE_REGISTRATION_MARK);
        return parsedText.substring(startIndex + Text.TEXT_MOT_TEST_NUMBER.length() + 1, lastIndex)
                .trim();
    }

    public String getVT30VRM(String parsedText) {
        int startIndex = parsedText.indexOf(Text.TEXT_VEHICLE_REGISTRATION_MARK);
        int lastIndex = parsedText.indexOf(Text.TEXT_VEHICLE_IDENTIFICATION_NUMBER);
        return parsedText.substring(startIndex + Text.TEXT_VEHICLE_REGISTRATION_MARK.length() + 1,
                lastIndex - 1);
    }

    public String getVT30Model(String parsedText) {
        int startIndex = parsedText.indexOf(Text.TEXT_MODEL);
        int lastIndex = parsedText.indexOf(Text.TEXT_COLOUR);
        return parsedText.substring(startIndex + Text.TEXT_MODEL.length() + 1, lastIndex).trim();
    }

    public String getVT32TestNumber(String parsedText) {
        int startIndex = parsedText.lastIndexOf(Text.TEXT_NOT_VALID);
        int lastIndex = parsedText.lastIndexOf(Text.TEXT_MAKE);
        return parsedText.substring(startIndex, lastIndex);
    }

    public String getVT32Make(String parsedText) {
        int startIndex = parsedText.lastIndexOf(Text.TEXT_NOT_VALID);
        int lastIndex = parsedText.lastIndexOf(Text.TEXT_MAKE);
        return parsedText.substring(startIndex, lastIndex);
    }

    public String getVT32ReInspectionMake(String parsedText) {
        int startIndex = parsedText.lastIndexOf(Text.TEXT_MAKE);
        int lastIndex = parsedText.lastIndexOf(Text.TEXT_MODEL);
        return parsedText.substring(startIndex + Text.TEXT_MAKE.length() + 1, lastIndex - 1);
    }

    public String getWelshVT32Make(String parsedText) {
        int startIndex = parsedText.lastIndexOf(Text.TEXT_MAKE);
        int lastIndex = parsedText.lastIndexOf(Text.TEXT_MODEL);
        return parsedText.substring(startIndex, lastIndex);
    }

    public String getVT32VRM(String parsedText) {
        int startIndex = parsedText.lastIndexOf(Text.TEXT_VEHICLE_REGISTRATION_MARK);
        int lastIndex = parsedText.lastIndexOf(Text.TEXT_MODEL);

        return parsedText.substring(startIndex + Text.TEXT_VEHICLE_REGISTRATION_MARK.length() + 1,
                lastIndex - 1);
    }

    public String getWelshVT32VIN(String parsedText) {
        int lastIndex = parsedText.lastIndexOf(Text.TEXT_ODOMETER_READING);
        int startIndex = parsedText.lastIndexOf(Text.TEXT_VEHICLE_IDENTIFICATION_NUMBER);

        return parsedText
                .substring(startIndex + Text.TEXT_VEHICLE_IDENTIFICATION_NUMBER.length() + 1,
                        lastIndex - 1);
    }

    public String getVT32VIN(String parsedText) {
        int startIndex = parsedText.lastIndexOf(Text.TEXT_NOT_VALID);
        int lastIndex = parsedText.lastIndexOf(Text.TEXT_VEHICLE_REGISTRATION_MARK);

        return parsedText.substring(startIndex, lastIndex - 1);
    }

    public String getVT32ReInspectionVIN(String parsedText) {
        int startIndex = parsedText.lastIndexOf(Text.TEXT_VEHICLE_IDENTIFICATION_NUMBER);
        int lastIndex = parsedText.lastIndexOf(Text.TEXT_MAKE);

        return parsedText
                .substring(startIndex + Text.TEXT_VEHICLE_IDENTIFICATION_NUMBER.length() + 1,
                        lastIndex - 1);
    }

    public String getVT32Model(String parsedText) {
        int startIndex = parsedText.lastIndexOf(Text.TEXT_MOT_TEST_NUMBER);
        int lastIndex = parsedText.lastIndexOf(Text.TEXT_SIGNATURE_OF_ISSUER);

        return parsedText
                .substring(startIndex + Text.TEXT_MOT_TEST_NUMBER.length() + 1, lastIndex - 1);
    }

    public String getCertificateTypes(String parsedText) {
        parsedText = parsedText.trim();
        int length = parsedText.length();

        String certificateType = parsedText.substring(length - 8, length - 4);
        return certificateType;
    }

    public String getVehicleDetailsFromCertificate(String parsedText) {
        int startIndex = parsedText.lastIndexOf(Text.TEXT_VEHICLE_IDENTIFICATION_NUMBER);
        int lastIndex = parsedText.indexOf(Text.TEXT_ISSUERS_NAME);

        return parsedText.substring(startIndex, lastIndex);
    }
    public String getVehicleMakeAndModelDetailsFromCertificate(String parsedText) {
        int startIndex = parsedText.lastIndexOf(Text.TEXT_MOT_TEST_NUMBER);
        int lastIndex = parsedText.indexOf(Text.TEXT_COLOUR);
        return parsedText.substring(startIndex, lastIndex);
    }
}
