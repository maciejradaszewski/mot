package com.dvsa.mot.selenium.datasource;

import java.util.Calendar;

/**
 * Created by davidd on 12/08/2014.
 */
public class OpeningHours {

    public static final OpeningHours STANDARD_WEEKDAY_HOURS =
            new OpeningHours(false, "9:00", "am", "5:00", "pm");
    public static final OpeningHours VTS_IS_CLOSED = new OpeningHours(true, "closed", "", "", "");
    public static final OpeningHours OPEN_ONE_TO_SIX =
            new OpeningHours(false, "1:00", "pm", "6:00", "pm");
    public static final OpeningHours INVALID_OPENING_HOURS =
            new OpeningHours(false, "9:15", "am", "5:00", "pm");

    private boolean isClosed;

    private String openingTime;
    private String openingPeriod;
    private String closingTime;
    private String closingPeriod;

    public OpeningHours(boolean isClosed, String openingTime, String openingPeriod,
            String closingTime, String closingPeriod) {
        this.isClosed = isClosed;
        this.openingTime = openingTime;
        this.openingPeriod = openingPeriod;
        this.closingTime = closingTime;
        this.closingPeriod = closingPeriod;
    }

    private static String amOrPm(int calendarAM_PMResult) {
        if (calendarAM_PMResult == 1)
            return "pm";
        else
            return "am";
    }

    private static int formatTime(int currentHour) {
        if (currentHour < 2)
            return 12;
        else
            return currentHour;
    }

    public static OpeningHours outsideOpeningHours() {
        Calendar calendar = Calendar.getInstance();
        int hourBehind = formatTime(calendar.get(Calendar.HOUR) - 1);
        String am_pm = amOrPm(calendar.get(Calendar.AM_PM));

        return new OpeningHours(false, String.valueOf(hourBehind - 1) + ":00", am_pm,
                String.valueOf(hourBehind) + ":00", am_pm);
    }

    public static String checkOpeningHoursString(OpeningHours openingHours) {
        if (openingHours.isClosed) {
            return "Closed";
        } else {
            String checkString = "";
            return checkString.concat(openingHours.openingTime + openingHours.openingPeriod + " to "
                    + openingHours.closingTime + openingHours.closingPeriod).trim();
        }
    }

    public boolean getIsClosed() {
        return this.isClosed;
    }

    public String getOpeningTime() {
        return this.openingTime;
    }

    public String getOpeningPeriod() {
        return this.openingPeriod;
    }

    public String getClosingTime() {
        return this.closingTime;
    }

    public String getClosingPeriod() {
        return this.closingPeriod;
    }

}
