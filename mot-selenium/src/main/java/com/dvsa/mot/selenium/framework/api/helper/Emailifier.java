package com.dvsa.mot.selenium.framework.api.helper;


public class Emailifier {
    public static String fromString(String str) {
        return str.replaceAll("[^.A-Za-z0-9@-]", "_").toLowerCase() + "@email.com";
    }
}
