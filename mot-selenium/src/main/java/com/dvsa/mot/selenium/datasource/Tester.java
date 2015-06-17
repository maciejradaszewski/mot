package com.dvsa.mot.selenium.datasource;

/**
 * Created by paweltom on 27/02/2014.
 */


/**
 * @author luke.evans
 *         Tester object
 */
public class Tester {

    public static final Tester TESTER_1 = new Tester(Person.TESTER_1_PERSON);
    public static final Tester TESTER_2 = new Tester(Person.TESTER_2_PERSON);

    public final Person person;

    public Tester(Person person) {
        this.person = person;
    }

    public static final String TESTER_NAME = "Name of tester";

}
