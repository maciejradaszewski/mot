package com.dvsa.mot.selenium.datasource;

public class MOTSearchDetails {
    public final String site;
    public final String tester;
    public final String regNumber;
    public final String vinChassisNumber;

    public MOTSearchDetails(String site, String tester, String regNumber, String vinChassisNumber) {
        this.site = site;
        this.tester = tester;
        this.regNumber = regNumber;
        this.vinChassisNumber = vinChassisNumber;
    }
}
