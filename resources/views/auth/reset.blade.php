<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Welcome</title>
        <link rel="stylesheet" type="text/css" href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="/vendor/twbs/bootstrap/dist/css/bootstrap-theme.min.css">
    </head>
    <body>
        <div class="container">
            <p>&nbsp;</p>
            <div class="col-sm-6 col-sm-offset-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">Reset password</div>
                    <div class="panel-body main-panel">
                        @include('_errors')
                        <form action="/password/reset" method="post" class="form-horizontal">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                            <input type="hidden" name="password"/>
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">E-mail</label>
                                <div class="col-sm-8">
                                    <input  type="email"
                                            name="email"
                                            class="form-control"
                                            value="{{old('email')}}"
                                    />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Password</label>
                                <div class="col-sm-8">
                                    <input  type="password"
                                            name="password"
                                            class="form-control"
                                    />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Repeat password</label>
                                <div class="col-sm-8">
                                    <input  type="password"
                                            name="password_confirmation"
                                            class="form-control"
                                    />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-8 col-sm-offset-4">
                                    <button type="submit" class="btn btn-primary">Reset password</button>
                                    <a class="" href="/auth/login">Back to login</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
