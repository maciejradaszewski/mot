package com.dvsa.mot.selenium.datasource;



public class BusinessDetails {

    /**
     * Business Details test data
     */
    public static final BusinessDetails BUSINESS_DETAILS_1 =
            new BusinessDetails(null, "K&C", "K&C Limited", "123456", CompanyType.Partnership,
                    "01180987655", "+44 161999 8888", "business1@email.com", "");
    public static final BusinessDetails BUSINESS_DETAILS_2 =
            new BusinessDetails(null, "Kwik Fit", "Kwik Fit Limited", "454332",
                    CompanyType.SoleTrader, "01180987655", "+44 261999 7777", "business2@email.dom",
                    "");
    public static final BusinessDetails BUSINESS_DETAILS_3 =
            new BusinessDetails(null, "Halfords", "Halfords Limited", "345600",
                    CompanyType.LimitedLiabilityPartnership, "01180987655", "+44 161229 6666",
                    "business3@email.dom", "");
    public static final BusinessDetails BUSINESS_DETAILS_4 =
            new BusinessDetails(null, "Autofix", "Autofix Limited", "006007", CompanyType.Company,
                    "01179009878", "+44 261966 1111", "business4@email.dom", "");
    public static final BusinessDetails BUSINESS_DETAILS_5 =
            new BusinessDetails(null, "Johns Motorcycle Garage", "Johns Motorcycle Garage",
                    "123456", CompanyType.Partnership, "01180987655", "+44 261966 8811",
                    "business1@email.dom", "v123539");
    public static final BusinessDetails BUSINESS_DETAILS_6 =
            new BusinessDetails(null, "FT Garage 1", "FT Garage 1", "454332",
                    CompanyType.SoleTrader, "01180987655", "+44 160066 7654", "business2@email.dom",
                    "v1261");
    public static final BusinessDetails BUSINESS_DETAILS_7 =
            new BusinessDetails(null, "UT Garage 1", "UT Garage 1", "345600",
                    CompanyType.LimitedLiabilityPartnership, "01180987655", "+44 131966 2233",
                    "business3@email.dom", "V1250");
    public static final BusinessDetails BUSINESS_DETAILS_8 =
            new BusinessDetails(null, "Johns Garage", "Johns Garage", "006007", CompanyType.Company,
                    "01179009879", "+44 261966 6789", "business4@email.dom", "v11234");
    public static final BusinessDetails BUSINESS_DETAILS_9 =
            new BusinessDetails(null, "Popular Garages", "Popular Garages", "006007",
                    CompanyType.Company, "01179009878", "+44 161900 1234", "business5@email.dom",
                    "V1234");
    public static final BusinessDetails BUSINESS_DETAILS_10 =
            new BusinessDetails(null, "FT Garage 4", "FT Garage 4", "006007", CompanyType.Company,
                    "01179009879", "+44 188800 1234", "business5@email.dom", "V1264");
    public static final BusinessDetails BUSINESS_DETAILS_11_LAPSED =
            new BusinessDetails(null, "Bancroft Garage", "Bancroft Garage", "006007",
                    CompanyType.Company, "01179009879", "+44 226600 1233", "business5@email.dom",
                    "V100817");
    public static final BusinessDetails BUSINESS_DETAILS_12_APPROVED_1 =
            new BusinessDetails(null, "Safari Garage", "Safari Garage", "006007",
                    CompanyType.Company, "01179009879", "+44 861911 9934", "business5@email.dom",
                    "31009");
    public static final BusinessDetails BUSINESS_DETAILS_12_APPROVED_2 =
            new BusinessDetails(null, "Panteg Service Station", "test-fit-no-Slots1", "006007",
                    CompanyType.Company, "01179009879", "+44 234900 3421", "business5@email.dom",
                    "31009");
    public static final BusinessDetails BUSINESS_DETAILS_13_REG_COMPANY =
            new BusinessDetails(null, "FitQuick", "F&Q Limited", "777888",
                    CompanyType.RegisteredCompany, "01180987655", "+44 231900 7766",
                    "business77@email.com", "");

    public static final BusinessDetails CRAZY_WHEELS =
            new BusinessDetails("B000004", "Crazy Wheels Inc.", "Crazy Wheels", "UK9102",
                    CompanyType.LimitedLiabilityPartnership, "0800-789-123", "", "central@isis.com",
                    "");
    public static final BusinessDetails EXAMPLE_AE_INC =
            new BusinessDetails("AE3412", "Example AE Inc.", "AE Example", "UK1283",
                    CompanyType.RegisteredCompany, "0800-789-321", "", "central@isis.com", "");


    public enum CompanyType {SoleTrader, Company, Partnership, LimitedLiabilityPartnership, RegisteredCompany}


    public final String AEnumber;
    public final String companyName;
    public final String tradingAs;
    public final String phoneNo;
    public final String faxNo;
    public final String emailAdd;
    public final String companyNo;
    public final String vtsNo;
    public final CompanyType companyType;

    public BusinessDetails(String AEnumber, String companyName, String tradingAs, String companyNo,
            CompanyType type, String phoneNo, String faxNo, String emailAdd, String vtsNo) {
        super();
        this.AEnumber = AEnumber;
        this.companyName = companyName;
        this.tradingAs = tradingAs;
        this.companyType = type;
        this.phoneNo = phoneNo;
        this.faxNo = faxNo;
        this.companyNo = companyNo;
        this.emailAdd = emailAdd;
        this.vtsNo = vtsNo;
    }

    //Ian Hyndman 23/01/2014
    //Invalid vehicle testing station number used for an enforcement search
    public class InvalidVTSNumber {
        public final static String INVALID_VTS_NUMBER_1 = "1ABC2345";
        public final static String INVALID_VTS_NUMBER_2 = "A12345";
    }


    //Ian Hyndman 24/01/2014
    //Invalid vehicle testing station number used for an enforcement search
    public class PartialVTSNumber {
        public final static String PARTIAL_VTS_NUMBER_1 = "V12";
        public final static String PARTIAL_VTS_NUMBER_2 = "123";
        public final static String PARTIAL_VTS_NUMBER_3 = "V126";
    }
}
