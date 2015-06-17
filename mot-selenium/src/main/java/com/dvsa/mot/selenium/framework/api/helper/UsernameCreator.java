package com.dvsa.mot.selenium.framework.api.helper;


public class UsernameCreator {

    public static String fromPersonName(String firstName, String surname) {
        return firstName + '.' + surname;
    }
}
