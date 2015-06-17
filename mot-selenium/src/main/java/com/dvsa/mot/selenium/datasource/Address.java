package com.dvsa.mot.selenium.datasource;

/**
 * Created by paweltom on 27/02/2014.
 */


/**
 * @author luke.evans
 *         Address object
 */
public class Address {

    public static final Address ADDRESS_ADDRESS1 =
            new Address("12", "Greenlands", "Filton Road", "Filton", "Bristol", "BS34 8TQ");
    public static final Address ADDRESS_ADDRESS2 =
            new Address("82", "Townhill Close", "Lime Kilne Close", "Swindon", "Wiltshire",
                    "BE9 6JJ");
    public static final Address ADDRESS_ADDRESS3 =
            new Address("38", "Eastmead Lane", "Sutton", "Surrey", "Surrey", "BT5 8DS");
    public static final Address ADDRESS_ADDRESS4 =
            new Address("99", "Failand Road", "Failand", "North Somerset", "Failand Town",
                    "BT5 8DS");
    public static final Address ADDRESS_ADDRESS5 =
            new Address("The Barn", "11 Country Lanee", "Dromore Road", "Dromore",
                    "Northern Ireland", "BT34 5TY");
    public static final Address AEDM1_ADDRESS =
            new Address("45", "Mill Park", "New Road", "Liverpool Industrial Estate", "Liverpool",
                    "BT3 5NJ");
    public static final Address AEDM2_ADDRESS =
            new Address("98", "Townhill Close", "Manchester Industrial Park", "Manchester City",
                    "Manchester", "BT2 4RR");
    public static final Address AEDM3_ADDRESS =
            new Address("23", "Gray Avenue", "Islington", "London", "", "BT5 8DS");
    public static final Address AEP1_ADDRESS =
            new Address("Unit 5", "Pennybirdge Estate", "New Road", "Belfast", "Northern Ireland",
                    "BT4 3HG");
    public static final Address AEP2_ADDRESS =
            new Address("Apple House", "11 Apple Lane", "Appleton", "London", "Central London",
                    "FT5 6II");
    public static final Address TESTER1_ADDRESS =
            new Address("1 Straw Hut", "5 Uncanny St", null, "Liverpool", "UK", "L1 1PQ");
    public static final Address ADDRESS_UTF8 =
            new Address("1 rhî, rhô", "Rèff", "Cymru", "èth", "", "ŵŷ");
    public static final Address POPULAR_GARAGES =
            new Address("67 Main Road", null, null, "Bristol", "Unreal County", "BS8 2NT");
    public static final Address TEST_FIT =
            new Address("2 Test Road", null, null, "Bristol", "England", "BS1 1NT");

    public static final Address ADDRESS_ISIS_INC =
            new Address("85 Vauxhall Cross", "", "", "London", "Little Bri", "SE11 5LL");
    public static final Address ADDRESS_CRAZY_WHEELS_INC = new Address("", "", "", "", "", "");

    public static final Address JOHNS_MOTORCYCLE_GARAGE =
            new Address("1 Test Road", null, null, "Bristol", "England", "BS1 1NT");
    public static final Address ADDRESS_EXAMPLE_AE_INC =
            new Address("85 Vauxhall Cross", "", "", "London", "Little Bri", "SE11 5LL");
    public static final Address MIKE_AND_JOHN_VTS =
            new Address("67 Main Road", null, null, "Bristol", "Unreal County", "BS8 2NT");
    public static final Address ANGEL_GARAGE =
            new Address("1 Test Road", null, null, "Bristol", "England", "BS1 1NT");
    public static final Address JOHNS_GARAGE =
            new Address("1 Test Road", null, null, "Bristol", "England", "BS1 1NT");



    //Authorised Examiners addresses
    public static final Address NEED_4_SPEED = new Address(null, null, null, null, null, null);
    public static final Address VENTURE_INDUSTRIES_AE =
            new Address(null, null, null, null, null, null);


    public final String line1;
    public final String line2;
    public final String line3;
    public final String town;
    public final String county;
    public final String postcode;



    public Address(String line1, String line2, String line3, String town, String county,
            String postcode) {
        super();
        this.line1 = line1;
        this.line2 = line2;
        this.line3 = line3;

        this.town = town;
        this.county = county;
        this.postcode = postcode;
    }




    public String getLine1() {
        return line1 != null ? line1 : "";
    }

    public String getLine2() {
        return line2 != null ? line2 : "";
    }

    public String getLine3() {
        return line3 != null ? line3 : "";
    }

    public String getTown() {
        return town != null ? town : "";
    }

    public String getCounty() {
        return county != null ? county : "";
    }

    public String getPostcode() {
        return postcode != null ? postcode : "";
    }

    public String getShortAddress() {
        return getLine1() + ", " + getTown();
    }

    public String getAddress() {
        return getLine1() + ", " + getLine2() + ", " + getTown() + ", " + getPostcode();
    }

    public static final String SITE_ADDRESS = "Site address";
    public static final String VTS_ADDRESS1_INCORRECT = "VTS1 address not correct!";
    public static final String VTS_ADDRESS2_INCORRECT = "VTS2 address not correct!";
    public static final String VTS_NAME1_INCORRECT = "VTS1 name not correct!";
    public static final String VTS_NAME2_INCORRECT = "VTS2 name not correct!";

}
