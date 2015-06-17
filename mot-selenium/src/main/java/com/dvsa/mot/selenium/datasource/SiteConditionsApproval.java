package com.dvsa.mot.selenium.datasource;

public class SiteConditionsApproval {
    public static final SiteConditionsApproval Site_Conditions_Approval_1 =
            new SiteConditionsApproval("V1234", "B000001", "inactivetester", "Popular Garages",
                    "Test1", "Level1", "30052014", "Class1Class3Class5Class7", "Catalyst", "Yes",
                    "No",
                    "June 2000 requirements (all Classes)August 1990 requirements (for Class 4)Pre July 1986 requirements (Ref. RfA for a VTS)November 2009 requirements (all Classes)",
                    "Recommend approval", "test comments", "Test1", "Grade1", "04062014",
                    "67 Main Road, Bristol, BS8 2NT");
    public static final SiteConditionsApproval Site_Conditions_Approval_2 =
            new SiteConditionsApproval("V1261", "B000004", "aedm", "FT Garage 1", "Test2", "Level2",
                    "30052014", "Class2Class3Class5Class7", "Diesel", "No", "Yes",
                    "September 1995 requirements (for Classes 3 and 4)Post July 1986 requirements (for Classes 3 and 4)2004 requirements (all Classes)",
                    "Recommend rejection", "test comments", "Test2", "Grade2", "04062014",
                    "11 Somewhere St, BS8 2NT");
    public static final SiteConditionsApproval Site_Conditions_Approval_3 =
            new SiteConditionsApproval("V12348", "B000004", "aedm", "test-fit-has-Slots1", "Test3",
                    "Level3", "30052014", "Class1Class3Class5Class7", "Both", "No", "No",
                    "Pre July 1986 requirements (Ref. RfA for a VTS)August 1990 requirements (for Class 4)",
                    "Recommend approval", "test comments", "Test1", "Grade1", "04062014",
                    "2 Test Road, Bristol, BS1 1NT");
    public static final SiteConditionsApproval Invalid_Date_Month_Year_1 =
            new SiteConditionsApproval("V1111", "", "", "", "", "", "35152016", "", "", "Yes",
                    "Yes", "", "", "", "", "", "35152016", "");
    public static final SiteConditionsApproval Invalid_Date_Month_Year_2 =
            new SiteConditionsApproval("", "", "", "", "", "", "a1b2cd34", "", "", "", "", "", "",
                    "", "", "", "a1b2cd34", "");
    public static final SiteConditionsApproval Invalid_Date_Month_Year_3 =
            new SiteConditionsApproval("", "", "", "", "", "", "00000000", "", "", "", "", "", "",
                    "", "", "", "00000000", "");
    public static final SiteConditionsApproval Invalid_Date_Month_Year_4 =
            new SiteConditionsApproval("", "", "", "", "", "", "36172013", "", "", "", "", "", "",
                    "", "", "", "36172013", "");
    public static final SiteConditionsApproval Invalid_Date_Month_Year_5 =
            new SiteConditionsApproval("", "", "", "", "", "", "31042014", "", "", "", "", "", "",
                    "", "", "", "31042014", "");
    public static final SiteConditionsApproval Invalid_Date_Month_Year_6 =
            new SiteConditionsApproval("", "", "", "", "", "", "25052014", "", "", "", "", "", "",
                    "", "", "", "25052014", "");
    public static final SiteConditionsApproval Invalid_Date_Month_Year_7 =
            new SiteConditionsApproval("", "", "", "", "", "", "16082011", "", "", "", "", "", "",
                    "", "", "", "16082011", "");
    public static final SiteConditionsApproval Invalid_Date_Month_Year_8 =
            new SiteConditionsApproval("", "", "", "", "", "", "-1-4-201", "", "", "", "", "", "",
                    "", "", "", "-1-4-201", "");
    public static final SiteConditionsApproval Invalid_Date_Month_Year_9 =
            new SiteConditionsApproval("", "", "", "", "", "", "29022014", "", "", "", "", "", "",
                    "", "", "", "29022014", "");
    /*public static final SiteConditionsApproval Invalid_Date_Month_Year_2 = new SiteConditionsApproval("","","","a1b2cd34", "", "");
	public static final SiteConditionsApproval Invalid_Date_Month_Year_3 = new SiteConditionsApproval("","","","00000000", "", "");
	public static final SiteConditionsApproval Invalid_Date_Month_Year_4 = new SiteConditionsApproval("V1234", "", "", "36172013","Directed site visit", "Satisfactory");
	public static final SiteConditionsApproval Invalid_Date_Month_Year_5 = new SiteConditionsApproval("V1234", "", "", "31042014", "Directed site visit", "Satisfactory");
	public static final SiteConditionsApproval Invalid_Date_Month_Year_6 = new SiteConditionsApproval("ABC123", "", "", "25052014", "Site approval visit", "Abandoned");
	public static final SiteConditionsApproval Invalid_Date_Month_Year_7 = new SiteConditionsApproval("","","", "16082011", "","");
	public static final SiteConditionsApproval Invalid_Date_Month_Year_8 = new SiteConditionsApproval("","","","-1-4-201", "", "");
	public static final SiteConditionsApproval Invalid_Date_Month_Year_9 = new SiteConditionsApproval("", "", "","29022014", "", "");*/

    public final String siteNumber;
    public final String AENumber;
    public final String AEName;
    public final String siteName;
    public final String interviewName;
    public final String interviewPosition;
    public final String visitDate;
    public final String classNumber;
    public final String fuelType;
    public final String atlMode;
    public final String optlMode;
    public final String appClass;
    public final String visitOutcome;
    public final String additionalComments;
    public final String veName;
    public final String veGrade;
    public final String date;
    public final String address;

    public SiteConditionsApproval(String siteNumber, String AENumber, String AEName,
            String siteName, String interviewName, String interviewPosition, String visitDate,
            String classNumber, String fuelType, String atlMode, String optlMode, String appClass,
            String visitOutcome, String additionalComments, String veName, String veGrade,
            String date, String address) {
        this.siteNumber = siteNumber;
        this.AENumber = AENumber;
        this.AEName = AEName;
        this.siteName = siteName;
        this.interviewName = interviewName;
        this.interviewPosition = interviewPosition;
        this.visitDate = visitDate;
        this.classNumber = classNumber;
        this.fuelType = fuelType;
        this.atlMode = atlMode;
        this.optlMode = optlMode;
        this.appClass = appClass;
        this.visitOutcome = visitOutcome;
        this.additionalComments = additionalComments;
        this.veName = veName;
        this.veGrade = veGrade;
        this.date = date;
        this.address = address;
    }

}
