package com.dvsa.mot.selenium.datasource;

import java.math.BigDecimal;

public class Payments {
    public static final BigDecimal COST_PER_SLOT = new BigDecimal("2.05");

    public static final Payments VALID_PAYMENTS =
            new Payments(25, "4006000000000600", "", "", "Tester1", "03", "2013", "03", "2016",
                    "111");
    public static final Payments INVALID_PAYMENTS_1 =
            new Payments(10000, "4012888888881881", "01012001", "01022001", "", "", "", "", "", "");
    public static final Payments INVALID_PAYMENTS_2 =
            new Payments(1, "", "", "", "", "", "", "", "", "");
    public static final Payments MAXIMUM_SLOTS =
            new Payments(75000, "4006000000000600", "", "", "Tester1", "03", "2013", "03", "2016",
                    "111");
    public static final Payments YYYYMMDD_DATES =
            new Payments(75000, "4006000000000600", "2001-01-01", "2002-01-01", "Tester1", "03", "2013", "03", "2016",
                    "111");
    
    


    public enum PaymentType {
        CARD("Card"), DIRECT_DEBIT("Direct Debit");

        private String paymentType;

        private PaymentType(String s) {
            paymentType = s;
        }

        public String getPaymentType() {
            return paymentType;
        }
    }


    public final int slots;
    public final String cardNumber;
    public final String fromDate;
    public final String toDate;
    public final String cardHolderName;
    public final String cardStartMonth;
    public final String cardStartYear;
    public final String cardEndMonth;
    public final String cardEndYear;
    public final String securityCode;

    public Payments(int slots, String cardNumber, String fromDate, String toDate,
            String cardHolderName, String cardStartMonth, String cardStartYear, String cardEndMonth,
            String cardEndYear, String securityCode) {
        this.slots = slots;
        this.cardNumber = cardNumber;
        this.fromDate = fromDate;
        this.toDate = toDate;
        this.cardHolderName = cardHolderName;
        this.cardStartMonth = cardStartMonth;
        this.cardStartYear = cardStartYear;
        this.cardEndMonth = cardEndMonth;
        this.cardEndYear = cardEndYear;
        this.securityCode = securityCode;
    }
}
