package uk.gov.dvsa.helper;

public class ContactDetailsHelper {

    public static String getAddressLine1() {
        return RandomDataGenerator.generateRandomString(8, 9);
    }

    public static String getAddressLine2() {
        return RandomDataGenerator.generateRandomString(8, 9);
    }

    public static String getAddressLine3() {
        return RandomDataGenerator.generateRandomString(8, 9);
    }

    public static String getCity() {
        return RandomDataGenerator.generateRandomString(8, 9);
    }

    public static String getPostCode() {
        return RandomDataGenerator.generateRandomString(8, 9);
    }

    public static String getPhoneNumber() {
        return RandomDataGenerator.generateRandomNumber(10, 9);
    }

    public static String getEmail() {
        return  RandomDataGenerator.generateEmail(20, System.nanoTime());
    }


    public static String generateUniqueName() {
        return RandomDataGenerator.generateRandomString(8, 9);
    }
}
