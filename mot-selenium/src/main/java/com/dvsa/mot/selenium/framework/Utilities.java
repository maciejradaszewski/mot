package com.dvsa.mot.selenium.framework;

import org.apache.commons.codec.binary.Base64;
import org.apache.commons.io.FileUtils;
import org.apache.commons.io.IOUtils;
import org.apache.pdfbox.cos.COSDocument;
import org.apache.pdfbox.pdfparser.PDFParser;
import org.apache.pdfbox.pdmodel.PDDocument;
import org.apache.pdfbox.util.PDFTextStripper;
import org.joda.time.DateTime;
import org.joda.time.DateTimeZone;
import org.joda.time.format.DateTimeFormat;
import org.openqa.selenium.OutputType;
import org.openqa.selenium.TakesScreenshot;
import org.openqa.selenium.WebDriver;
import org.testng.Reporter;

import java.io.*;
import java.net.HttpURLConnection;
import java.net.URL;
import java.sql.Time;
import java.text.*;
import java.text.Format;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;



public class Utilities {

    public static class Logger {

        /**
         * Logs a message in TestNG and Standard log<p>
         * <b>RETURNS:</b><p>
         * N/A
         */
        public static void LogInfo(String logText) {
            String logInfo = ("INFO: [" + logText + "]");
            //Output to TestNG log and Standard log
            Reporter.log(logInfo, true);
        }

        /**
         * Logs an error to TestNG and Standard log<p>
         * <b>RETURNS:</b><p>
         * N/A
         */
        public static void LogError(String logText) {
            String logErr = ("ERROR: [" + logText + "]");
            //Output to TestNG log and Standard log
            Reporter.log(logErr, true);
        }

        /**
         * Logs an error and prints the exception details to TestNG and Standard log<p>
         * <b>RETURNS:</b><p>
         * N/A
         */
        public static void LogError(String logText, Exception e) {
            StringWriter stringWriter = new StringWriter();
            PrintWriter printWriter = new PrintWriter(stringWriter);
            e.printStackTrace(printWriter);

            String logErr = ("ERROR: [" + logText + "]\n" +
                    stringWriter.toString());

            //Output to TestNG log and Standard log
            Reporter.log(logErr, true);
        }
    }

    public static String convertBase64ToString(String s) {
        byte[] decoded = Base64.decodeBase64(s.getBytes());
        return new String(decoded);
    }

    public static String dateTimeToString(DateTime date) {
        return date.withZone(DateTimeZone.UTC)
                .toString(DateTimeFormat.forPattern("YYYY-MM-dd"));
    }

    public static Date getDate(int year, int month, int day) {
        Calendar c = Calendar.getInstance();
        c.set(year, month, day);
        return new Date(c.getTimeInMillis());
    }

    public static DateTime getPreviousMonthsDate() {
        DateTime dt = DateTime.now();
        return dt.minusMonths(1);
    }
    public static String getFutureDate() {
        Calendar cal = Calendar.getInstance();
        cal.add(Calendar.DATE, 1);
        SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd");
        String formatted = format.format(cal.getTime());
        return formatted;
    }


    public static void takeScreenShot(WebDriver driver, String filename, String destinationPath) {
        try {
            File scrFile = ((TakesScreenshot) driver).getScreenshotAs(OutputType.FILE);
            File screenshotFile = new File(destinationPath + "/" + filename);

            if (!screenshotFile.exists()) {
                FileUtils.copyFile(scrFile, screenshotFile);
                Utilities.Logger.LogInfo("PageUrl: " + driver.getCurrentUrl());
                Utilities.Logger
                        .LogInfo("Screenshot saved to: " + screenshotFile.getAbsolutePath());
            }
        } catch (Exception e) {
            Utilities.Logger.LogError("Error trying to take screen shot: " + e.getMessage(), e);
        }
    }

    public static String convertDateToGDSFormat(DateTime date) {
        return (date.toString(DateTimeFormat.forPattern("d MMMM YYYY")));
    }

    public static String  getSystemDateAndTime(){
        Calendar date=Calendar.getInstance();
        date.setTime(new Date());
        Format f = new SimpleDateFormat("ddMMyyhhmmss");
        String d = f.format(date.getTime());
        return d;

    }

    public static long getTimeDifference(String appTime){
        Calendar date=Calendar.getInstance();
        date.setTime(new Date());

        SimpleDateFormat formatter = new SimpleDateFormat("h:mmaa");
        String d = formatter.format(date.getTime());
        Date appDate = null, sysDate = null;
        try{
            appDate = formatter.parse(appTime);
            sysDate = formatter.parse(d);
        }
        catch (ParseException e){
            e.printStackTrace();
        }
        long diff = 0;
        if(appDate.getTime() >= sysDate.getTime())
            diff = appDate.getTime()-sysDate.getTime();
        else
            diff = sysDate.getTime() - appDate.getTime();
        return diff/(60 * 1000);
    }

    private static final String USER_AGENT = "Mozilla/5.0 (java edition)";
    private static final String COOKIE_NAME = "iPlanetDirectoryPro";

    //Fetches a URL from the mot-web-frontend using binary-safe methods using the existing session cookie from the browser under control.
    public static byte[] getUrl(String url, WebDriver driver) throws Exception {


        URL obj = new URL(url);
        HttpURLConnection con = (HttpURLConnection) obj.openConnection();
        con.setRequestMethod("GET");
        con.setRequestProperty("User-Agent", USER_AGENT);
        con.setRequestProperty("Cookie",
                driver.manage().getCookieNamed(COOKIE_NAME).toString());

        int responseCode = con.getResponseCode();

        InputStream in = new BufferedInputStream(con.getInputStream());
        StringBuffer response = new StringBuffer();
        ByteArrayOutputStream output = new ByteArrayOutputStream();
        byte[] buffer = new byte[200000];
        int i;

        while (0 <= (i = in.read())) {
            output.write(i);
        }
        in.close();
        byte[] pdfBytes = output.toByteArray();

        return pdfBytes;
    }

    public static void copyUrlBytesToFile(String pdfUrl, WebDriver driver, String pathNFileName) throws IOException{
        byte[] downloadedFile = new byte[200000];
        try {
            downloadedFile = Utilities.getUrl(pdfUrl,driver);
        } catch (Exception e) {
            System.out.println(e);
        }

        File targetFile = new File(pathNFileName);
        File parent = targetFile.getParentFile();
        if(!parent.exists()){
            parent.mkdirs();
        }

        FileOutputStream output = new FileOutputStream(targetFile);

        ByteArrayInputStream in =new ByteArrayInputStream(downloadedFile);

        IOUtils.copy(in, output);
        in.close();
        output.close();
    }

    public static String pdfToText(String pathNFileName)
    {
        PDFParser parser = null;
        String parsedText = null;
        PDFTextStripper pdfStripper = null;
        PDDocument pdDoc = null;
        COSDocument cosDoc = null;
        File file = new File(pathNFileName);
        if (!file.isFile()) {
            System.err.println("File " + pathNFileName + " does not exist.");
        }
        try
        {
            parser = new PDFParser(new FileInputStream(file));
        }
        catch (IOException e)
        {
            System.err.println("Unable to open PDF Parser. " + e.getMessage());
        }
        try
        {
            parser.parse();
            cosDoc = parser.getDocument();
            pdfStripper = new PDFTextStripper();
            pdDoc = new PDDocument(cosDoc);
            pdfStripper.setStartPage(1);
            pdfStripper.setEndPage(5);
            parsedText = pdfStripper.getText(pdDoc);
        }
        catch (Exception e)
        {
            System.err.println("An exception occured in parsing the PDF Document."+ e.getMessage());
        }
        finally
        {
            try
            {
                if (cosDoc != null)
                    cosDoc.close();
                if (pdDoc != null)
                    pdDoc.close();
            }
            catch (Exception e)
            {
                e.printStackTrace();
            }
        }
        return parsedText;
    }

}
