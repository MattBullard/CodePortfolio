<script type="text/javascript">
        function PrintWindow()
        {                     
           window.print();            
           CheckWindowState(); 
        }
        
        function CheckWindowState()
        {            
            if(document.readyState=="complete")
            {
                window.close();  
            }
            else
            {            
                setTimeout("CheckWindowState()", 2000)
            }
        }    
        
       PrintWindow();
</script>
