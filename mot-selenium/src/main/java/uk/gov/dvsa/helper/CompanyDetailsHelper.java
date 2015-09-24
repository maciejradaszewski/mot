package uk.gov.dvsa.helper;

public class CompanyDetailsHelper {

    public static String businessName;
    public static String tradingName;
    public static String businessType;
    public static String companyNumber;

    public static String generateUniqueCompanyName() {
        return RandomDataGenerator.generateRandomString();
    }

    public static String generateUniqueCompanyNumber() {
        return RandomDataGenerator.generateRandomNumber(8,6);
    }

    public static void setCompanyDetails() {
        businessName = generateUniqueCompanyName();
        tradingName = generateUniqueCompanyName();
        businessType = CompanyType.Company.getName();
        companyNumber = generateUniqueCompanyNumber();
    }
}
