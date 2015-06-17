package com.dvsa.mot.selenium.datasource;

import java.util.Calendar;

public class Experience {

    public static final Experience PRESENT_EXPERIENCE1 =
            new Experience("10", "March", "1990", "21", "June", "1999",
                    "Ran MOT checks on cars mechanical duties", "Kwik-Fit",
                    "Value must be less than or equal to 2014.",
                    "1. Start date is not valid\n2. End date is not valid");

    public static final Experience PRESENT_EXPERIENCE2 =
            new Experience("10", "March", "2000", "11", "June", "2004",
                    "Ran MOT checks on cars mechanical duties", "London-MOT-Centre",
                    "Value must be less than or equal to 2014.",
                    "1. Start date is not valid\n2. End date is not valid");

    public static final Experience FUTURE_EXPERIENCE = new Experience("21", "April",
            String.valueOf(Calendar.getInstance().get(Calendar.YEAR) + 1), "21", "June",
            String.valueOf(Calendar.getInstance().get(Calendar.YEAR) + 1),
            "Ran MOT checks on cars mechanical duties", "Bristol-MOT-Centre",
            "Start date can't be in the future ",
            "1. Start date is not valid\n2. End date is not valid");

    public static final Experience FUTURE_EXPERIENCE1 = new Experience("31", "February",
            String.valueOf(Calendar.getInstance().get(Calendar.YEAR) + 1), "21", "June",
            String.valueOf(Calendar.getInstance().get(Calendar.YEAR) + 1),
            "Ran MOT checks on cars mechanical duties", "Bristol-MOT-Centre",
            "Start date can't be in the future ",
            "1. Start date is not valid\n2. End date is not valid");

    public String employerName;
    public String startDay;
    public String startMonth;
    public String startYear;
    public String endDay;
    public String endMonth;
    public String endYear;
    public String descriptionOfDuties;
    public String invalidYearAlert;
    public String datesNotValid;

    public Experience(String startDay, String startMonth, String startYear, String endDay,
            String endMonth, String endYear, String descriptionOfDuties, String employerName,
            String invalidYearAlert, String datesNotValid) {
        super();

        this.startDay = startDay;
        this.startMonth = startMonth;
        this.startYear = startYear;
        this.endDay = endDay;
        this.endMonth = endMonth;
        this.endYear = endYear;
        this.descriptionOfDuties = descriptionOfDuties;
        this.employerName = employerName;
        this.invalidYearAlert = invalidYearAlert;
        this.datesNotValid = datesNotValid;

    }

}
