package com.dvsa.mot.selenium.datasource;

import java.util.Calendar;

public class Conviction {
    public final static Conviction CURRENT_CONVICTION_BURGLARY =
            new Conviction("Burglary", "Exeter", "1", "January",
                    String.valueOf(Calendar.getInstance().get(Calendar.YEAR)));
    public final static Conviction FUTURE_CONVICTION_THEFT =
            new Conviction("Theft", " London", "21", "March",
                    String.valueOf(Calendar.getInstance().get(Calendar.YEAR) + 1));
    public final static Conviction PAST_CONVICTION_SPEEDING =
            new Conviction("Speeding", "Livepool", "1", "June",
                    String.valueOf(Calendar.getInstance().get(Calendar.YEAR) - 11));

    public final String offence;
    public final String court;
    public final String day;
    public final String month;
    public final String year;

    public Conviction(String offence, String court, String day, String month, String year) {
        super();
        this.offence = offence;
        this.court = court;
        this.day = day;
        this.month = month;
        this.year = year;
    }

}
