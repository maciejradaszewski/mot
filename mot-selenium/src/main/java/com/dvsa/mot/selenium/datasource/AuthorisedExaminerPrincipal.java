package com.dvsa.mot.selenium.datasource;

import com.dvsa.mot.selenium.datasource.Person.Gender;

public class AuthorisedExaminerPrincipal {

    /**
     * Decorator pattern should be applied rather than the Composite one for the 'person' field.
     * It's done this way due to not sticking to the JavaBeans convention (we use public final fields).
     * TODO: To be discussed.
     */


    public static final AuthorisedExaminerPrincipal AEP_1 = new AuthorisedExaminerPrincipal(
            new Person(null, "Mr", "Tom", "William", "McKay", 1953, 2, "May", Gender.Male,
                    "tmckay@hotmail.com", "abc@abc.com", "011786656777", Address.AEP1_ADDRESS,
                    "RMDIN757025CJ90001", null,null,null), "MCKA3211", DrivingLicence.AEP1_DRIVINGLICENCENO);

    public final Person person;
    public final String userID;
    public final DrivingLicence licNo;

    public AuthorisedExaminerPrincipal(Person person, String userID, DrivingLicence licNo) {

        this.person = person;
        this.userID = userID;
        this.licNo = licNo;
    }
}
