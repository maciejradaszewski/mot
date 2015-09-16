package uk.gov.dvsa.helper;

public class CompanyDetailsHelper {

    public static String getCompanyName() {
        return RandomDataGenerator.generateRandomString();
    }

    public static String getBusinessName() {
        return RandomDataGenerator.generateRandomString();
    }

    public static String getCompanyNumber() {
        return RandomDataGenerator.generateRandomNumber(8,6);
    }

    public static String getTradingName()
    {
        return RandomDataGenerator.generateRandomString();
    }

    public static String getBusinessType()
    {
        return CompanyType.Company.getName();
    }
}
