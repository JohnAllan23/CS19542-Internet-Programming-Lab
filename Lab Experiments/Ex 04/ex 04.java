package demo;

import java.io.IOException;
import java.io.PrintWriter;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

@WebServlet("/Exp4")
public class Demo extends HttpServlet {
    private static final long serialVersionUID = 1L;

    public Demo() {
        super();
    }

    protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
        response.getWriter().append("Served at: ").append(request.getContextPath());
    }

    protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
        response.setContentType("text/html");
        PrintWriter out = response.getWriter();

        // Retrieve form parameters
        String uname = request.getParameter("name");
        String age = request.getParameter("age");
        String phone = request.getParameter("phone");

        // Display the submitted data in response
        out.println("<h1 style='color:red'>Name: " + uname + "</h1>");
        out.println("<h1 style='color:blue'>Age: " + age + "</h1>");
        out.println("<h1 style='color:green'>Phone Number: " + phone + "</h1>");
    }
}
