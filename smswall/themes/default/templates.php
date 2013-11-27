
<script id="tpl_tweet" type="text/template">
<li id="t<%= id %>" class="<%= visible ? 'msgOK' : 'msgNO' %>" style="display: none;">
    <% if(config.avatar == 1){ %>
        <img src="<%= avatar %>" class="avatar" />
    <% } %>
    <div class="twut-text">
        <span class="author"><%= author %> :</span>
        <span class="textMsg"><%= message %></span> -
        <span class="time" data-time="<%= ctime %>"><%= moment(ctime).fromNow() %></span>
        <div style="clear: both;"></div>
    </div>
</li>
</script>

<script id="tpl_bulle" type="text/template">
<div id="splash" style="display: block;" >
    <% if(config.avatar == 1){ %>
        <img src="<%= avatar %>" class="avatarbig" />
    <% } %>
    <span class="author"><%= author %></span>
    <span class="textMsg"><%= message %></span>
    <span class="time" data-time="<%= ctime %>"><%= moment(ctime).fromNow() %></span>
    <div style="clear: both;"></div>
</div>
</script>