package com.dvsa.mot.selenium.datasource;

import com.dvsa.mot.selenium.datasource.Person.Gender;


public class AuthorisedExaminerDesignatedManager {

    /**
     * Decorator pattern should be applied rather than the Composite one for the 'person' field.
     * It's done this way due to not sticking to the JavaBeans convention (we use public final fields).
     * TODO: To be discussed.
     */
    public static final AuthorisedExaminerDesignatedManager AEDM1_DESIGNATED_MANAGER =
            new AuthorisedExaminerDesignatedManager(
                    new Person(null, "Mr", "Will", "Thomas", "Jones", 1953, 1, "April", Gender.Male,
                            "wjones@email.com", "abc@abc.com", "01176351234", Address.AEDM1_ADDRESS,
                            "RMAIN757025KJ90001", null, null, null), "JONE1234", "01179083454", "07890987123",
                    DrivingLicence.AEDM1_DRIVINGLICENCENO, true);
    public static final AuthorisedExaminerDesignatedManager AEDM2_DESIGNATED_MANAGER =
            new AuthorisedExaminerDesignatedManager(
                    new Person(null, "Miss", "Hannah", "Jane", "Douglas", 1953, 1, "April",
                            Gender.Female, "hdouglas@email.com", "abc@abc.com", "01176351234",
                            Address.AEDM2_ADDRESS, "ROBIN757025CJ99901", null, null, null), "DOUG4321",
                    "02046547632", "07677654345", DrivingLicence.AEDM2_DRIVINGLICENCENO, true);
    public static final AuthorisedExaminerDesignatedManager AEDM3_DESIGNATED_MANAGER =
            new AuthorisedExaminerDesignatedManager(
                    new Person(null, "Mr", "Andy", "Ian", "Marks", 1953, 1, "April", Gender.Male,
                            "amarks@email.com", "abc@abc.com", "01176351234", Address.AEDM3_ADDRESS,
                            "RMAIN757025KJ90661", null, null, null), "MARK0987", "02083232345", "07988434343",
                    DrivingLicence.AEDM3_DRIVINGLICENCENO, true);
    public static final AuthorisedExaminerDesignatedManager AEDM4_DESIGNATED_MANAGER_UTF8_ADDRESS =
            new AuthorisedExaminerDesignatedManager(
                    new Person(null, "Mr", "Andy", "Ian", "Marks", 1953, 1, "April", Gender.Male,
                            "amarks@email.com", "abc@abc.com", "01176351234", Address.ADDRESS_UTF8,
                            "RMDIN757025CJ90001", null, null, null), "MARK0987", "02083232345", "07988434343",
                    DrivingLicence.AEDM3_DRIVINGLICENCENO, true);


    public final Person person;
    public final String userID;
    public final DrivingLicence licNo;
    public final String telMob;
    public final String telHome;
    public final boolean dvsaTrainingCompleted;

    public AuthorisedExaminerDesignatedManager(Person person, String userID, String telHome,
            String telMob, DrivingLicence licNo, boolean dvsaTrainingCompleted) {

        this.person = person;
        this.userID = userID;
        this.telHome = telHome;
        this.telMob = telMob;
        this.licNo = licNo;
        this.dvsaTrainingCompleted = dvsaTrainingCompleted;
    }
}
