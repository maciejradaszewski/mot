package com.dvsa.mot.selenium.datasource;

import com.dvsa.mot.selenium.framework.RandomDataGenerator;
import com.dvsa.mot.selenium.framework.Utilities;

import java.text.DateFormatSymbols;
import java.util.Calendar;

public class Person {

    public static final Person PERSON_1 =
            new Person(null, "Mr", "Joe", "Mike", "Hughes", 1953, 19, "January", Gender.Male,
                    "joemikehughes@email.com", "abc@abc.com", "01170987767",
                    Address.ADDRESS_ADDRESS1, "RMAIN757025KJ90001", null, "", "");
    public static final Person PERSON_2 =
            new Person(null, "Mr", "Dan", "Bill", "Brown", 1936, 12, "January", Gender.Male,
                    "dbrown@email.com", "abc@abc.com", "01178900021", Address.ADDRESS_ADDRESS2,
                    "ROBIN757025CJ99901", null, "", "");
    public static final Person PERSON_3 =
            new Person(null, "Mrs", "Karen", "Nicola", "Smyth", 1989, 5, "January", Gender.Female,
                    "karensm@email.com", "abc@abc.com", "01176351234", Address.ADDRESS_ADDRESS3,
                    "RMAIN757025KJ90661", null, "", "");
    public static final Person PERSON_4 =
            new Person(null, "Miss", "Tara", "Winnie", "McCabe", 1973, 11, "January", Gender.Female,
                    "kaz@email.com", "abc@abc.com", "011786656777", Address.ADDRESS_ADDRESS4,
                    "RMDIN757025CJ90001", null, "", "");
    public static final Person TESTER_1_PERSON =
            new Person(null, "Mrs", "Jan", "Nowak", "Smith", 1978, 28, "January", Gender.Female,
                    "hays@email.dom", "abc@abc.com", "01170900087", Address.ADDRESS_ADDRESS5,
                    "ROBIN757025CJ99991", null, "", "");

    public static final Person TESTER_2_PERSON =
            new Person(null, "Miss", "Glen", "David", "Maxwell",
                    Integer.valueOf(Calendar.getInstance().get(Calendar.YEAR) - 16),
                    Integer.valueOf(Calendar.getInstance().get(Calendar.DAY_OF_MONTH) + 1),
                    new DateFormatSymbols().getMonths()[Calendar.getInstance().get(Calendar.MONTH)],
                    Gender.Female, "maxwell@hotmail.com", "glen@yahoo.com", "02070903287",
                    Address.ADDRESS_ADDRESS1, "ROBIN757025CJ99901", null, "", "");

    //Existing Persons
    public static final Person BOB_THOMAS =
            new Person("5", "Mr", "Bob", "Thomas", "Arctor Tester1", 1981, 24, "April", Gender.Male,
                    "dummy@email.com", null, "+768-45-4433630", Address.TESTER1_ADDRESS, null,
                    Login.LOGIN_TESTER1, "blah", "blah");

    public static final Person testNameCertif4 =
            new Person("3003", "Mr", "Shreyashwini", "Srinika", "Gunda", 1981, 24, "April", Gender.Male,
                    "Srinivas.Gunda@valtech.co.uk", null, "+768-45-4433630", Address.TESTER1_ADDRESS, null,
                    Login.LOGIN_TESTER1, "answer", "answer");

    public static final Person MALLORY_ARCHER =
            new Person("29", "Mr", "Mallory", null, "Archer", 1920, 9, "November", Gender.Male,
                    null, null, null, null, null, Login.LOGIN_AEDM, "", "");
    public static final Person PAM_POOVEY =
            new Person("30", "Mrs", "Pam", null, "Poovey", 1948, 3, "November", Gender.Female, null,
                    null, null, null, null, Login.LOGIN_AED1, "", "");
    public static final Person CHERYL_CHARLENE_TUNT =
            new Person("31", "Mrs", "Cheryl", "Charlene", "Tunt", 1948, 11, "March", Gender.Female,
                    null, null, null, null, null, Login.LOGIN_AED2, "", "");
    public static final Person DEMO_TEST_USER =
            new Person("32", "Mr", "Demo", "Mot Test", "User", 1978, 11, "March", Gender.Male, null,
                    null, null, null, null, Login.LOGIN_DEMO_TEST_USER, "", "");
    public static final Person DM_USER =
            new Person("23", "Mr", "Bob", "Thomas", "Arctor", 1981, 24, "April", Gender.Male, null,
                    null, null, null, null, Login.DM_USER, "", "");
    public static final Person TESTER2 =
            new Person("18", "Mr", "Bob", "Thomas", "Arctor", 1981, 24, "April", Gender.Male, null,
                    null, null, null, null, Login.LOGIN_TESTER2, "", "");
    public static final Person TESTER8 =
            new Person("35", "Mr", "Bob", "Thomas", "Arctor", 1981, 24, "April", Gender.Male, null,
                    null, null, null, null, Login.LOGIN_TESTER8, "", "");
    public static final Person NEW_USER =
            new Person("21", "Mr", "Bob", "Thomas", "Arctor", 1978, 11, "March", Gender.Male, null,
                    null, null, null, null, Login.LOGIN_TESTER5, "", "");


    public enum Gender {Male, Female}


    public final String id;
    public final String title;
    public final String forename;
    public final String middleName;
    public final String surname;
    public final int year;
    public final int day;
    public final String month;
    public final String email;
    public final String wrongEmail;
    public final String telNo;
    public final Gender gender;
    public final Address address;
    public final String drivingLicence;
    public final Login login;
    public final String securityAnswer1;
    public final String securityAnswer2;

    public Person(String id, String title, String forename, String middleName, String surname,
            int year, int day, String month, Gender gender, String email, String wrongEmail,
            String telNo, Address address, String licence, Login login, String securityAnswer1,
            String securityAnswer2) {
        this.id = id;
        this.title = title;
        this.forename = forename;
        this.middleName = middleName;
        this.surname = surname;
        this.year = year;
        this.day = day;
        this.month = month;
        this.email = email;
        this.wrongEmail = wrongEmail;
        this.telNo = telNo;
        this.gender = gender;
        this.address = address;
        this.drivingLicence = licence;
        this.login = login;
        this.securityAnswer1 = securityAnswer1;
        this.securityAnswer2 = securityAnswer2;
    }

    /**
     * Create a unique "Person" based on the Person supplied. Adds the uniqueString to the email address, may be made more
     * complex in future.
     *
     * @param person       Person to base the new Person on
     * @param uniqueString Non-random uniqueString to use when creating the new user
     * @return
     */
    public static Person getUnique(Person person, String uniqueString) {
        String personEmail = person.email == null ?
                null :
                RandomDataGenerator.generateRandomString(10, uniqueString.hashCode())
                        + person.email;
        String personWrongEmail = person.wrongEmail == null ?
                null :
                RandomDataGenerator.generateRandomString(10, uniqueString.hashCode())
                        + person.wrongEmail;

        Utilities.Logger.LogInfo("Generated unique person with email " + personEmail +
                "and wrongEmail " + personWrongEmail);

        return new Person(null, person.title, person.forename, person.middleName, person.surname,
                person.year, person.day, person.month, person.gender, personEmail, personWrongEmail,
                person.telNo, person.address, person.drivingLicence, null, null, null);
    }

    public String getName() {
        return forename != null ? forename : "";
    }

    public String getMiddleName() {
        return middleName != null ? middleName : "";
    }

    public String getSurname() {
        return surname != null ? surname : "";
    }

    public String getId() {
        return this.id;
    }

    public String getFullName() {
        return getTitle() + " " + getName() + (hasMiddleName() ? " " + getMiddleName() : "") + " "
                + getSurname();
    }
    public String getFullNameWithOutTitle() {
        return getName() + (hasMiddleName() ? " " + getMiddleName() : "") + " "
                + getSurname();
    }

    public String getNamesAndSurname() {
        return getName() + (hasMiddleName() ? " " + getMiddleName() : "") + " " + getSurname();
    }

    public Login getLogin() {

        return this.login;
    }

    public String getAddress() {
        return address.getAddress();
    }

    public String getEmail() {
        return email;
    }

    public String getTelNo() {
        return telNo;
    }

    //TODO Refactor. Create Date variable for dof and update test to user dd-MMMM-yyyy pattern to format this date
    public String getDateOfBirth() {
        return day + " " + month + " " + year;
    }

    public String getTitle() {
        return this.title;
    }

    private boolean hasMiddleName() {
        return middleName != null && !middleName.isEmpty();
    }
}
