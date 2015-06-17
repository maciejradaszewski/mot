package com.dvsa.mot.selenium.datasource.enums;

import java.util.Calendar;

/**
 * Created by davidd on 29/07/2014.
 */
public enum Days {
    SUNDAY("Sunday", 0),
    MONDAY("Monday", 1),
    TUESDAY("Tuesday", 2),
    WEDNESDAY("Wednesday", 3),
    THURSDAY("Thursday", 4),
    FRIDAY("Friday", 5),
    SATURDAY("Saturday", 6);


    private final String dayName;
    private final int dayNumber;

    private Days(String dayName, int dayNumber) {
        this.dayName = dayName;
        this.dayNumber = dayNumber;
    }

    public String getDayName() {
        return dayName;
    }

    public static Days getCurrentDay() {
        Calendar calendar = Calendar.getInstance();
        Days[] daysOfTheWeek = new Days[7];

        for (Days days : Days.values()) {
            daysOfTheWeek[days.dayNumber] = days;
        }

        return daysOfTheWeek[calendar.get(Calendar.DAY_OF_WEEK) - 1];
    }
}
