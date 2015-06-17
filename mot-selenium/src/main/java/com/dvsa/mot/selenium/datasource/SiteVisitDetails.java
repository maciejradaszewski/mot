package com.dvsa.mot.selenium.datasource;

public class SiteVisitDetails {
    //public static final SiteVisitDetails Site_Visit_Details_1 = new SiteVisitDetails("V1234", "Popular Garages", "67 Main Road, Bristol, BS8 2NT", "22042014", "Directed site visit", "Satisfactory");
    //public static final SiteVisitDetails Site_Visit_Details_2 = new SiteVisitDetails("V1234","Popular Garages","67 Main Road, Bristol, BS8 2NT", "15012013", "Site approval visit", "Shortcomings found");
    public static final SiteVisitDetails Site_Visit_Details_1 =
            new SiteVisitDetails("V1234", "Popular Garages", " 67 Main Road, Bristol, BS8 2NT",
                    "22042014", "Directed site visit", "Satisfactory");
    public static final SiteVisitDetails Site_Visit_Details_2 =
            new SiteVisitDetails("V1264", "FT Garage 4", " 14 Loads of Slots St, Bristol, BS6 2NQ",
                    "15012013", "Site approval visit", "Shortcomings found");
    public static final SiteVisitDetails Invalid_Date_Month_Year_1 =
            new SiteVisitDetails("", "", "", "35152016", "", "");
    public static final SiteVisitDetails Invalid_Date_Month_Year_2 =
            new SiteVisitDetails("", "", "", "a1b2cd34", "", "");
    public static final SiteVisitDetails Invalid_Date_Month_Year_3 =
            new SiteVisitDetails("", "", "", "00000000", "", "");
    public static final SiteVisitDetails Invalid_Date_Month_Year_4 =
            new SiteVisitDetails("V1234", "", "", "36172013", "Directed site visit",
                    "Satisfactory");
    public static final SiteVisitDetails Invalid_Date_Month_Year_5 =
            new SiteVisitDetails("V1234", "", "", "31042014", "Directed site visit",
                    "Satisfactory");
    public static final SiteVisitDetails Invalid_Date_Month_Year_6 =
            new SiteVisitDetails("ABC123", "", "", "25052014", "Site approval visit", "Abandoned");
    public static final SiteVisitDetails Invalid_Date_Month_Year_7 =
            new SiteVisitDetails("", "", "", "16082011", "", "");
    public static final SiteVisitDetails Invalid_Date_Month_Year_8 =
            new SiteVisitDetails("", "", "", "-1-4-201", "", "");
    public static final SiteVisitDetails Invalid_Date_Month_Year_9 =
            new SiteVisitDetails("", "", "", "29022014", "", "");

    public final String siteNumber;
    public final String name;
    public final String Address;
    public final String date;
    public final String visitReason;
    public final String visitOutcome;

    public SiteVisitDetails(String siteNumber, String name, String Address, String date,
            String visitReason, String visitOutcome) {
        this.siteNumber = siteNumber;
        this.name = name;
        this.Address = Address;
        this.date = date;
        this.visitReason = visitReason;
        this.visitOutcome = visitOutcome;
    }

}
