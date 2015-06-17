package com.dvsa.mot.selenium.datasource;

public class CreditCard {
    public static final CreditCard VALID_CREDIT_CARD =
            new CreditCard("1111222233334444", "08", "2017", "123", "111", "01", "2000");
    public static final CreditCard INVALID_CREDIT_CARD =
            new CreditCard("1234567890", "03", "2018", "000", "222", "12", "2002");


    public final String number;
    public final String expireMonth;
    public final String expireYear;
    public final String securityCode;
    public final String issueNumber;
    public final String startMonth;
    public final String startYear;


    public CreditCard(String number, String expireMonth, String expireYear, String securityCode,
            String issueNumber, String startMonth, String startYear) {
        super();
        this.number = number;
        this.expireMonth = expireMonth;
        this.expireYear = expireYear;
        this.securityCode = securityCode;
        this.issueNumber = issueNumber;
        this.startMonth = startMonth;
        this.startYear = startYear;
    }
}
