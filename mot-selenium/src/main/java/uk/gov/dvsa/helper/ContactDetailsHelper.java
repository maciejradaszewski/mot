package uk.gov.dvsa.helper;

public class ContactDetailsHelper {

    public static String addressLine1;
    public static String addressLine2;
    public static String addressLine3;
    public static String city;
    public static String postCode;

    public static String phoneNumber;
    public static String email;

    public static String generateUniqueName() {
        return RandomDataGenerator.generateRandomString(8,9);
    }

    public static String generateUniqueNumber() {
        return RandomDataGenerator.generateRandomNumber(10, 9);
    }

    public static String generateUniqueEmail() {
        return  RandomDataGenerator.generateEmail(20, System.nanoTime());
    }

    public static void setContactDetails() {
        addressLine1 = generateUniqueName();
        addressLine2 = generateUniqueName();
        addressLine3 = generateUniqueName();
        city = generateUniqueName();
        postCode = generateUniqueName();

        phoneNumber = generateUniqueNumber();
        email = generateUniqueEmail();
    }
}
